<?php
// Include your database credentials

require_once("../utils/defines.php");


/**
 * Class InstagramInteractionRemover
 * This class handles the logic for interacting with the database to fetch and remove targets
 * that have generated less than 5 interactions per session and job.
 */
class InstagramInteractionRemover {
    
    private $pdo; // PDO instance
    private $username; // Instagram username provided as a parameter

    /**
     * Constructor to initialize the class with the PDO instance and username.
     * @param PDO $pdo
     * @param string $username
     */
    public function __construct($pdo, $username) {
        $this->pdo = $pdo;
        $this->username = $username;
    }

    /**
     * Get session IDs and their blogger_followers/blogger_post_likers JSON from the last 2 days.
     * @return array Rows with session_id, blogger_followers, blogger_post_likers.
     */
    private function getRecentSessions() {
        $query = "
            SELECT session_id, blogger_followers, blogger_post_likers
            FROM instagram_session
            WHERE instagram_username_interact = :username
              AND start_time >= DATE_SUB(NOW(), INTERVAL 2 DAY)
            ORDER BY start_time DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $this->username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch the targets with less than 5 interactions per session_id and job_name.
     * @param array $sessionIds The list of session IDs to filter by.
     * @return array The array of targets to be removed.
     */
    /**
     * Build target→job_name map from instagram_session JSON columns,
     * then verify interaction counts against the 800 threshold.
     * @param array $sessions Rows from getRecentSessions().
     * @return array Targets flagged for removal with their job_name.
     */
    private function getTargetsToRemove($sessions) {
        if (empty($sessions)) {
            return [];
        }

        // Step 1: Build target→job map from session JSON columns
        // A target may appear in multiple sessions; track all session_ids per target/job key
        $targetJobSessions = []; // ['target|job_name' => [session_id, ...]]

        foreach ($sessions as $session) {
            $sessionId = $session['session_id'];

            $jobColumns = [
                'blogger-followers'    => $session['blogger_followers'],
                'blogger-post-likers'  => $session['blogger_post_likers'],
            ];

            foreach ($jobColumns as $jobName => $jsonValue) {
                if ($jsonValue === null || $jsonValue === 'null') {
                    continue;
                }
                $targets = json_decode($jsonValue, true);
                if (!is_array($targets)) {
                    continue;
                }
                foreach ($targets as $target) {
                    $key = $target . '|' . $jobName;
                    if (!isset($targetJobSessions[$key])) {
                        $targetJobSessions[$key] = [];
                    }
                    $targetJobSessions[$key][] = $sessionId;
                }
            }
        }

        if (empty($targetJobSessions)) {
            echo "[DEBUG] No targets extracted from session JSON columns.\n";
            return [];
        }

        echo "[DEBUG] Target/job map built: " . count($targetJobSessions) . " entries\n";
        foreach ($targetJobSessions as $key => $sids) {
            echo "[DEBUG]   $key => sessions: " . implode(',', $sids) . "\n";
        }

        // Step 2: For each target/job, count interactions per session in instagram_interact
        $targetsToRemove = [];

        foreach ($targetJobSessions as $key => $sessionIds) {
            list($target, $jobName) = explode('|', $key, 2);

            $inQuery = implode(',', array_fill(0, count($sessionIds), '?'));
            $query = "
                SELECT session_id, COUNT(*) as interactions
                FROM instagram_interact
                WHERE instagram_username_interact = ?
                  AND target = ?
                  AND session_id IN ($inQuery)
                  AND is_unlist = 0
                  AND unfollowed = 0
                GROUP BY session_id
            ";

            $params = array_merge([$this->username, $target], $sessionIds);
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Guard: skip if no interaction rows at all (configured but never interacted)
            if (empty($rows)) {
                echo "[DEBUG] $target / $jobName — skipped (0 interactions in instagram_interact)\n";
                continue;
            }

            // Check all sessions are below threshold
            $allBelowThreshold = true;
            foreach ($rows as $row) {
                echo "[DEBUG] $target / $jobName — session {$row['session_id']}: {$row['interactions']} interactions\n";
                if ((int)$row['interactions'] >= 800) {
                    $allBelowThreshold = false;
                    break;
                }
            }

            if ($allBelowThreshold) {
                echo "[DEBUG] $target / $jobName — FLAGGED for removal\n";
                $targetsToRemove[] = [
                    'target'   => $target,
                    'job_name' => $jobName,
                ];
            } else {
                echo "[DEBUG] $target / $jobName — kept (threshold reached)\n";
            }
        }

        return $targetsToRemove;
    }



/**
 * Insert targets to be removed into instagram_remove_config,
 * skipping entries that already exist with is_removed = 0.
 *
 * @param array $targets The array of targets to insert.
 * @return void
 */
private function insertTargetsToRemove($targets) {
    $checkQuery = "
        SELECT COUNT(*)
        FROM instagram_remove_config
        WHERE target = :target
          AND job_name = :job_name
          AND instagram_username_interact = :instagram_username_interact
          AND is_removed = 0";

    $insertQuery = "
        INSERT INTO instagram_remove_config (target, job_name, instagram_username_interact, is_removed, date_created)
        VALUES (:target, :job_name, :instagram_username_interact, 0, NOW())";

    $checkStmt  = $this->pdo->prepare($checkQuery);
    $insertStmt = $this->pdo->prepare($insertQuery);

    foreach ($targets as $target) {
        $checkStmt->execute([
            'target'                      => $target['target'],
            'job_name'                    => $target['job_name'],
            'instagram_username_interact' => $this->username,
        ]);

        $count = $checkStmt->fetchColumn();

        if ($count == 0) {
            $insertStmt->execute([
                'target'                      => $target['target'],
                'job_name'                    => $target['job_name'],
                'instagram_username_interact' => $this->username,
            ]);
            echo "Inserted: '{$target['target']}' / '{$target['job_name']}' for '{$this->username}'.\n";
        } else {
            echo "Skipped (already exists): '{$target['target']}' / '{$target['job_name']}'.\n";
        }
    }
}


    /**
     * Main function to run the logic for fetching and removing targets.
     */
    public function run() {
        // Step 1: Get sessions from the last 2 days
        $sessions = $this->getRecentSessions();

        echo "[DEBUG] Sessions found: " . count($sessions) . "\n";
        foreach ($sessions as $s) {
            echo "[DEBUG] session_id={$s['session_id']} blogger_followers={$s['blogger_followers']} blogger_post_likers={$s['blogger_post_likers']}\n";
        }

        // Step 2: Get targets below the interaction threshold
        $targetsToRemove = $this->getTargetsToRemove($sessions);

        // Step 3: Insert the targets into the instagram_target_interact_remove table
        if (!empty($targetsToRemove)) {
            $this->insertTargetsToRemove($targetsToRemove);
            echo "Targets removed successfully.\n";
        } else {
            echo "No targets to remove.\n";
        }
    }
}

// Check if the script is being called with the correct parameters
if ($argc != 2) {
    echo "Usage: php mycode.php <username>\n";
    exit(1);
}

// Retrieve the username from the command line arguments
$username = $argv[1];

// Initialize the PDO connection (MySQL database)
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=$DBNAME", $DBUSER, $DBPASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    print("ICI");
    echo "Connection failed: " . $e->getMessage();
    exit(1);
}

// Create an instance of the InstagramInteractionRemover class
$remover = new InstagramInteractionRemover($pdo, $username);

// Run the script
$remover->run();
?>

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
     * Get the last three session IDs for the given username.
     * @return array The array of last three session_ids.
     */
    private function getLastThreeSessions() {
        $query = "
            SELECT session_id 
            FROM instagram_session
            WHERE instagram_username_interact = :username
            ORDER BY start_time DESC
            LIMIT 10";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $this->username]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Fetch the targets with less than 5 interactions per session_id and job_name.
     * @param array $sessionIds The list of session IDs to filter by.
     * @return array The array of targets to be removed.
     */
    /*private function getTargetsToRemove($sessionIds) {
        if (empty($sessionIds)) {
            return [];
        }
        
        $inQuery = implode(',', array_fill(0, count($sessionIds), '?'));
        
        $query = "
            SELECT target, job_name, session_id, COUNT(*) as interactions
            FROM instagram_interact
            WHERE instagram_username_interact = ?
            AND session_id IN ($inQuery)
            GROUP BY target, job_name, session_id
            HAVING interactions < 5";
            
        
        $stmt = $this->pdo->prepare($query);
        $params = array_merge([$this->username], $sessionIds);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/
    /**
 * Fetch the targets where all sessions have fewer than 5 interactions per session_id and job_name.
 * The target must appear in all three sessions to be considered for removal.
 * @param array $sessionIds The list of session IDs to filter by.
 * @return array The array of targets to be removed.
 */
private function getTargetsToRemove($sessionIds) {
    if (empty($sessionIds)) {
        return [];
    }

    $inQuery = implode(',', array_fill(0, count($sessionIds), '?'));

    // Step 1: Retrieve the counts of interactions
    $query = "
        SELECT target, job_name, session_id, COUNT(*) as interactions
        FROM instagram_interact
        WHERE instagram_username_interact = ?
        AND session_id IN ($inQuery)
        AND is_unlist=0
        AND unfollowed=0
        GROUP BY target, job_name, session_id
        ";

    $stmt = $this->pdo->prepare($query);
    $params = array_merge([$this->username], $sessionIds);
    $stmt->execute($params);

    // Step 2: Fetch results and filter targets
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $targetsToRemove = [];

    // Check if all sessions for each target and job_name are below the threshold
    $targetSessionMap = [];
    foreach ($results as $row) {
        $key = $row['target'] . '|' . $row['job_name']; // Create a unique key for target/job_name combination

        if (!isset($targetSessionMap[$key])) {
            $targetSessionMap[$key] = []; // Initialize array for this target/job_name
        }

        // Store the interaction count for the session
        $targetSessionMap[$key][$row['session_id']] = $row['interactions'];
    }

    //print_r($targetSessionMap);
    // Filter targets based on interaction counts
    foreach ($targetSessionMap as $key => $sessions) {
        // Ensure the target appears in exactly three sessions
        
        if (count($sessions) >= 1) {
            $allBelowThreshold = true;

	    // Check if all sessions have interactions less than 5
	    // 13-06-2026 : Enhance the threeshold to 800 in order to remove target after each run
            foreach ($sessions as $interactions) {
                if ($interactions >= 800) {
                    $allBelowThreshold = false;
                    break; // No need to check further if one session exceeds the threshold
                }
            }

            // If all sessions are below the threshold, add to targets to remove
            if ($allBelowThreshold) {
                list($target, $jobName) = explode('|', $key);
                $targetsToRemove[] = [
                    'target' => $target,
                    'job_name' => $jobName,
                    'sessions' => $sessions
                ];
            }
        }
    }

    return $targetsToRemove;
}



/**
 * Insert the targets to be removed into the instagram_target_interact_remove table,
 * only if there is no existing entry with the same target_name, job_name, and taget_instagram_interact_username.
 * 
 * @param array $targets The array of targets to insert.
 * @return void
 */
private function insertTargetsToRemove($targets) {
    // Query to check if a target with the same target_name, job_name, and username already exists
    $checkQuery = "
        SELECT COUNT(*) 
        FROM instagram_target_interact_remove
        WHERE target_name = :target_name 
        AND job_name = :job_name 
        AND taget_instagram_interact_username = :taget_instagram_interact_username";
    
    // Query to insert a new target
    $insertQuery = "
        INSERT INTO instagram_target_interact_remove (target_name, job_name, date_created, taget_instagram_interact_username)
        VALUES (:target_name, :job_name, NOW(), :taget_instagram_interact_username)";

    // Prepare the queries
    $checkStmt = $this->pdo->prepare($checkQuery);
    $insertStmt = $this->pdo->prepare($insertQuery);

    foreach ($targets as $target) {
        // Check if the target_name, job_name, and username already exist in the table
        $checkStmt->execute([
            'target_name' => $target['target'],
            'job_name' => $target['job_name'],
            'taget_instagram_interact_username' => $this->username, // the username being processed
        ]);

        // Fetch the result (COUNT value)
        $count = $checkStmt->fetchColumn();

        if ($count == 0) {
            // If no existing entry found, insert the new target
            $insertStmt->execute([
                'target_name' => $target['target'],
                'job_name' => $target['job_name'],
                'taget_instagram_interact_username' => $this->username,
            ]);
        } else {
            // Optionally log or print that the target already exists
            echo "Target '{$target['target']}' with job '{$target['job_name']}' for user '{$this->username}' already exists, skipping insert.\n";
        }
    }
}


    /**
     * Main function to run the logic for fetching and removing targets.
     */
    public function run() {
        // Step 1: Get the last three session IDs
        $sessionIds = $this->getLastThreeSessions();

        // Step 2: Get the targets with less than 5 interactions
        $targetsToRemove = $this->getTargetsToRemove($sessionIds);

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

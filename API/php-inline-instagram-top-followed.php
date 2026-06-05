<?php

require_once("../utils/defines.php");

class InstagramInteraction {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves the last session IDs for a given username.
     * 
     * @param string $username The Instagram username to filter sessions.
     * @return array An array of session IDs.
     */
    private function getLastSessionIds($username) {
        // SQL query to get the last 10 session IDs for the username
        $sql = "
            SELECT session_id 
            FROM instagram_session 
            WHERE instagram_username_interact = :username 
            ORDER BY start_time DESC 
            LIMIT 20
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getTopFollowedTargets($username) {
        $sessionIds = $this->getLastSessionIds($username);
        
        // Create named parameters for each session ID
        $sessionIdsPlaceholder = implode(',', array_map(function($i) {
            return ":session_id_$i";
        }, array_keys($sessionIds)));
    
        // SQL query to retrieve top targets with the greatest followed number per session_id
        /*
        $sql = "
            SELECT target, job_name, MAX(followed) AS max_followed, SUM(followed) AS total_followed
            FROM instagram_interact
            WHERE instagram_username_interact = :username 
              AND session_id IN ($sessionIdsPlaceholder)
              AND unfollowed=0
              AND  is_unlist=0
              AND  followed=1
            GROUP BY target, job_name
            HAVING COUNT(DISTINCT session_id) >= 2
            ORDER BY max_followed DESC
            LIMIT 8
        ";*/
        $sql="
            SELECT target, job_name, SUM(followed) AS total_followed
            FROM instagram_interact
            WHERE instagram_username_interact = :username 
              AND session_id IN ($sessionIdsPlaceholder)
            AND unfollowed = 0
            AND is_unlist = 0
            GROUP BY target, job_name
            HAVING COUNT(DISTINCT session_id) >= 2
            ORDER BY total_followed DESC
            LIMIT 6";

    
        $stmt = $this->pdo->prepare($sql);
        
        // Bind the parameters
        $params = ['username' => $username];
        foreach ($sessionIds as $i => $sessionId) {
            $params["session_id_$i"] = $sessionId;
        }
    
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
 * Inserts the top followed targets into the database.
 * If the entry already exists, it updates the existing entry.
 * 
 * @param string $username The Instagram username to filter interactions.
 */
public function insertTopFollowedTargets($username) {
    $topFollowedTargets = $this->getTopFollowedTargets($username);

    foreach ($topFollowedTargets as $target) {
        // Check if the entry already exists
        $checkStmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM instagram_target_top_followed 
            WHERE target_name = :target_name 
              AND job_name = :job_name 
              AND target_instagram_interact_username = :taget_instagram_interact_username
        ");

        $checkStmt->execute([
            'target_name' => $target['target'],
            'job_name' => $target['job_name'],
            'taget_instagram_interact_username' => $username,
        ]);

        $exists = $checkStmt->fetchColumn() > 0; // Check if any row exists

        if ($exists) {
            // Update the existing entry
            $updateStmt = $this->pdo->prepare("
                UPDATE instagram_target_top_followed 
                SET followed = :followed, date_updated = NOW() 
                WHERE target_name = :target_name 
                  AND job_name = :job_name 
                  AND target_instagram_interact_username = :taget_instagram_interact_username
            ");

            $updateStmt->execute([
                'followed' => $target['total_followed'],
                'target_name' => $target['target'],
                'job_name' => $target['job_name'],
                'taget_instagram_interact_username' => $username,
            ]);
        } else {
            // Insert a new entry
            $insertStmt = $this->pdo->prepare("
                INSERT INTO instagram_target_top_followed (target_name, job_name, date_created, target_instagram_interact_username, followed)
                VALUES (:target_name, :job_name, NOW(), :taget_instagram_interact_username, :followed)
            ");

            $insertStmt->execute([
                'target_name' => $target['target'],
                'job_name' => $target['job_name'],
                'taget_instagram_interact_username' => $username,
                'followed' => $target['total_followed']
            ]);
        }
    }
}

    
}

// Check if a username was passed as an argument
if ($argc < 2) {
    echo "Usage: php  program_name <username>\n";
    exit(1);
}

// Get the username from the command line argument
$username = $argv[1];

// Create a new PDO instance (replace with your actual DB connection details)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBUSER, $DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create an instance of InstagramInteraction
    $instagramInteraction = new InstagramInteraction($pdo);
    
    // Insert top followed targets for the provided username
    $instagramInteraction->insertTopFollowedTargets($username);

    echo "Top followed targets for '$username' have been inserted successfully.\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}



?>

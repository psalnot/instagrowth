<?php
require_once("../utils/defines.php");
require_once("../class/mysql.class.php");

class InstagramConfigUpdater
{
    private $db;

    public function __construct($DBUSER, $DBPASS, $DBNAME)
    {
        $msql = new MMsql();
        $this->db = $msql->dbconnect($DBUSER, $DBPASS, $DBNAME);
    }


    /**
     * Update remove config
     *
     * @param string $instagram_username_interact
     * @param string $datee  Date in format YYYY-MM-DD
     */
    public function updateRemoveConfig($instagram_username_interact, $datee)
    {
        $dateMinus2 = date('Y-m-d', strtotime($datee . ' -2 days'));

        // Fetch targets and job_name from last 2 days
        $sql = "
            SELECT target, job_name
            FROM instagram_interact
            WHERE instagram_username_interact = ?
              AND date_created > ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $instagram_username_interact, $dateMinus2);
        $stmt->execute();
        $result = $stmt->get_result();

        $usedTargets = [];

        while ($row = $result->fetch_assoc()) {
            $target = $row['target'];
            $jobName = $row['job_name'];

            // Skip duplicates in same run
            if (in_array($target, $usedTargets)) {
                continue;
            }

            // Check if already exists with is_removed = 0
            $checkSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_remove_config
                WHERE target = ?
                  AND instagram_username_interact = ?
                  AND job_name = ?
                  AND is_removed = 0
            ";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bind_param("sss", $target, $instagram_username_interact, $jobName);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $countRow = $checkResult->fetch_assoc();
            $exists = $countRow['cnt'] ?? 0;

            if ($exists == 0) {
                // Insert new entry
                $insertSql = "
                    INSERT INTO instagram_remove_config (target, instagram_username_interact, job_name, is_removed, session_id)
                    VALUES (?, ?, ?, 0, 42)
                ";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->bind_param("sss", $target, $instagram_username_interact, $jobName);
                $insertStmt->execute();

                echo "Inserted into remove_config: $target / $instagram_username_interact / $jobName\n";
            } else {
                echo "Skipped (already exists): $target / $instagram_username_interact / $jobName\n";
            }

            $usedTargets[] = $target;
        }
    }


    /**
     * Update config likers
     *
     * @param string $instagram_username_interact
     * @param string $datee  Date in format YYYY-MM-DD
     */
    public function updateConfigLikers($instagram_username_interact, $datee)
    {
        $dateMinus2 = date('Y-m-d', strtotime($datee . ' -2 days'));

        $sql = "
            WITH total_follows AS (
                SELECT 
                    target,
                    COUNT(*) AS total_follows
                FROM instagram_interact
                WHERE instagram_username_interact = ?
                  AND followed = 1
                GROUP BY target
            ),
            follow_backs AS (
                SELECT 
                    target,
                    COUNT(*) AS follow_backs
                FROM instagram_notifications
                WHERE instagram_username_interact = ?
                  AND is_follow_back = 1
                GROUP BY target
            )
            SELECT 
                tf.target,
                COALESCE(fb.follow_backs, 0) AS follow_backs,
                tf.total_follows,
                ROUND(CAST(COALESCE(fb.follow_backs, 0) AS DECIMAL(10,4)) / tf.total_follows, 4) AS follow_back_percentage
            FROM total_follows tf
            LEFT JOIN follow_backs fb ON tf.target = fb.target
            ORDER BY follow_back_percentage DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $instagram_username_interact, $instagram_username_interact);
        $stmt->execute();
        $result = $stmt->get_result();

        $usedUsernames = [];
        $insertCount = 0;

        while ($row = $result->fetch_assoc()) {
            $target = $row['target'];
            $percentage = (float)$row['follow_back_percentage'];

            if ($percentage <= 0.1) {
                continue;
            }

            if (in_array($target, $usedUsernames)) {
                continue;
            }

            // Check last 2 days
            $checkSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_interact
                WHERE target = ?
                  AND instagram_username_interact = ?
                  AND date_created > ?
            ";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bind_param("sss", $target, $instagram_username_interact, $dateMinus2);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $countRow = $checkResult->fetch_assoc();
            $count = $countRow['cnt'] ?? 0;

            if ($count == 0) {
                // Insert row
                $insertSql = "
                    INSERT INTO instagram_add_config 
                        (instagram_username_interact, instagram_username, job_name, is_added)
                    VALUES (?, ?, 'blogger-post-likers', 1)
                ";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->bind_param("ss", $instagram_username_interact, $target);
                $insertStmt->execute();

                echo "Inserted: $instagram_username_interact / $target\n";
                $insertCount++;
            }

            $usedUsernames[] = $target;

            if ($insertCount >= 3) {
                break; // limit to 3 inserts
            }
        }
    }
    /**
     * Update config followers
     *
     * @param string $instagram_username_interact
     * @param string $datee  Date in format YYYY-MM-DD
     */
    public function updateConfigFollowers($instagram_username_interact, $datee)
    {
        $dateMinus2 = date('Y-m-d', strtotime($datee . ' -2 days'));

        // Main SQL
        $sql = "
            SELECT 
                n.target,
                n.instagram_username AS follower_username,
                i.last_interaction,
                i.job_name,
                i.followers,
                i.following,
                i.posts_count,
                t.follow_count
            FROM instagram_notifications n
            JOIN (
                SELECT 
                    target,
                    COUNT(*) AS follow_count
                FROM instagram_notifications
                WHERE instagram_username_interact = ?
                  AND is_followed = 1
                  AND last_interaction >= ?
                GROUP BY target
                ORDER BY follow_count DESC
                LIMIT 6
            ) t ON n.target = t.target
            JOIN (
                SELECT 
                    ii.instagram_username,
                    ii.target,
                    ii.instagram_username_interact,
                    ii.last_interaction,
                    ii.job_name,
                    ii.followers,
                    ii.following,
                    ii.posts_count
                FROM instagram_interact ii
                INNER JOIN (
                    SELECT 
                        instagram_username,
                        target,
                        instagram_username_interact,
                        MAX(last_interaction) AS max_last_interaction
                    FROM instagram_interact
                    GROUP BY instagram_username, target, instagram_username_interact
                ) latest
                ON ii.instagram_username = latest.instagram_username
                   AND ii.target = latest.target
                   AND ii.instagram_username_interact = latest.instagram_username_interact
                   AND ii.last_interaction = latest.max_last_interaction
            ) i
            ON n.instagram_username = i.instagram_username
               AND n.target = i.target
               AND n.instagram_username_interact = i.instagram_username_interact
            WHERE n.instagram_username_interact = ?
              AND n.is_followed = 1
              AND n.last_interaction >= ?
            ORDER BY t.follow_count DESC, n.target, i.last_interaction DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $instagram_username_interact, $datee, $instagram_username_interact, $datee);
        $stmt->execute();
        $result = $stmt->get_result();

        $usedUsernames = [];

        while ($row = $result->fetch_assoc()) {
            $follower_username = $row['follower_username'];
            $target = $row['target'];
            $followers = $row['followers'];
            $following = $row['following'];

            // skip duplicates
            if (in_array($follower_username, $usedUsernames)) {
                continue;
	    }
	    print "Follower user_name $follower_username\n";

            // Check last 2 days
            $checkSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_interact
                WHERE target = ?
                  AND instagram_username_interact = ?
                  AND date_created > ?
            ";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bind_param("sss", $follower_username, $instagram_username_interact, $dateMinus2);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $countRow = $checkResult->fetch_assoc();
            $count = $countRow['cnt'] ?? 0;

            if ($count == 0) {
                // Insert
                $insertSql = "
                    INSERT INTO instagram_add_config 
                        (instagram_username_interact, instagram_username, target, job_name, is_added, followers, following)
                    VALUES (?, ?, ?, 'blogger-followers', 1, ?, ?)
";
		print "SQL request: $insertSql\n";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->bind_param("sssss", $instagram_username_interact, $follower_username, $target, $followers, $following);
                $insertStmt->execute();
            }

            $usedUsernames[] = $follower_username;
        }
    }

    
}

// ---------- Inline execution ----------
if (php_sapi_name() === 'cli') {
    if ($argc < 3) {
        echo "Usage: php " . $argv[0] . " instagram_username_interact datee(YYYY-MM-DD)\n";
        exit(1);
    }

    $instagram_username_interact = $argv[1];
    $datee = $argv[2];

    // Replace with your DB credentials
    //$DBUSER = "your_db_user";
    //$DBPASS = "your_db_pass";
    //$DBNAME = "your_db_name";

    $updater = new InstagramConfigUpdater($DBUSER, $DBPASS, $DBNAME);
    #$updater->updateConfigFollowers($instagram_username_interact, $datee);
    #$updater->updateConfigLikers($instagram_username_interact,$datee);
    $updater->updateRemoveConfig($instagram_username_interact,$datee);

    echo "Update completed.\n";
}
?>


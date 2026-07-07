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
                ROUND(CAST(COALESCE(fb.follow_backs, 0) AS DECIMAL(10,4)) / tf.total_follows, 4) AS follow_back_percentage,
                COALESCE(lf.followers, 0) AS followers
            FROM total_follows tf
            LEFT JOIN follow_backs fb ON tf.target = fb.target
            LEFT JOIN (
                SELECT target, followers
                FROM instagram_interact ii
                WHERE instagram_username_interact = ?
                  AND followers IS NOT NULL
                  AND last_interaction = (
                      SELECT MAX(last_interaction)
                      FROM instagram_interact
                      WHERE target = ii.target
                        AND instagram_username_interact = ii.instagram_username_interact
                        AND followers IS NOT NULL
                  )
                GROUP BY target
            ) lf ON tf.target = lf.target
            ORDER BY follow_back_percentage DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $instagram_username_interact, $instagram_username_interact, $instagram_username_interact);
        $stmt->execute();
        $result = $stmt->get_result();

        $usedUsernames = [];
        $insertCount = 0;

        while ($row = $result->fetch_assoc()) {
            $target = $row['target'];
            $percentage = (float)$row['follow_back_percentage'];
            $followers = (int)$row['followers'];

            if ($percentage <= 0.1 || $followers < 5000) {
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
                // Cross-job dedup: skip if already added under any job
                $dedupSql = "
                    SELECT COUNT(*) as cnt
                    FROM instagram_add_config
                    WHERE instagram_username_interact = ?
                      AND instagram_username = ?
                ";
                $dedupStmt = $this->db->prepare($dedupSql);
                $dedupStmt->bind_param("ss", $instagram_username_interact, $target);
                $dedupStmt->execute();
                $dedupRow = $dedupStmt->get_result()->fetch_assoc();
                if (($dedupRow['cnt'] ?? 0) > 0) {
                    echo "Skipped (exists in another job): $instagram_username_interact / $target\n";
                    $usedUsernames[] = $target;
                    continue;
                }

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
                // Cross-job dedup: skip if already added under any job
                $dedupSql = "
                    SELECT COUNT(*) as cnt
                    FROM instagram_add_config
                    WHERE instagram_username_interact = ?
                      AND instagram_username = ?
                ";
                $dedupStmt = $this->db->prepare($dedupSql);
                $dedupStmt->bind_param("ss", $instagram_username_interact, $follower_username);
                $dedupStmt->execute();
                $dedupRow = $dedupStmt->get_result()->fetch_assoc();
                if (($dedupRow['cnt'] ?? 0) > 0) {
                    echo "Skipped (exists in another job): $instagram_username_interact / $follower_username\n";
                    $usedUsernames[] = $follower_username;
                    continue;
                }

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

    /**
     * Update config likers based on instagram_session.blogger_post_likers.
     * Only considers sessions with successful_interactions > 10.
     * Only adds targets with follow-back rate > 10% not used in the last 2 days.
     *
     * @param string $instagram_username_interact
     */
    public function updateConfigLikerSession($instagram_username_interact)
    {
        $dateMinus2 = date('Y-m-d', strtotime('-2 days'));

        // Step 1: Fetch sessions from last 2 days with successful_interactions > 10
        $sessionSql = "
            SELECT session_id, blogger_post_likers
            FROM instagram_session
            WHERE instagram_username_interact = ?
              AND successful_interactions > 10
              AND blogger_post_likers IS NOT NULL
              AND blogger_post_likers != 'null'
        ";
        $sessionStmt = $this->db->prepare($sessionSql);
        $sessionStmt->bind_param("s", $instagram_username_interact);
        $sessionStmt->execute();
        $sessionResult = $sessionStmt->get_result();

        // Step 2: Build unique target list from JSON columns
        $targets = [];
        while ($row = $sessionResult->fetch_assoc()) {
            $decoded = json_decode($row['blogger_post_likers'], true);
            // Handle double-encoded JSON
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            if (!is_array($decoded)) {
                continue;
            }
            foreach ($decoded as $target) {
                $targets[$target] = true;
            }
        }
        $sessionStmt->close();

        if (empty($targets)) {
            echo "No targets found in recent sessions.\n";
            return;
        }

        $targets = array_keys($targets);
        echo "Targets from sessions: " . implode(', ', $targets) . "\n";

        $insertCount = 0;

        foreach ($targets as $target) {

            // Step 3: Recency guard — skip if used in last 2 days
            $recentSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_interact
                WHERE target = ?
                  AND instagram_username_interact = ?
                  AND date_created > ?
            ";
            $recentStmt = $this->db->prepare($recentSql);
            $recentStmt->bind_param("sss", $target, $instagram_username_interact, $dateMinus2);
            $recentStmt->execute();
            $recentRow = $recentStmt->get_result()->fetch_assoc();
            $recentStmt->close();

            if (($recentRow['cnt'] ?? 0) > 0) {
                echo "Skipped (used in last 2 days): $target\n";
                continue;
            }

            // Step 4: Calculate follow-back rate for this target
            $totalSql = "
                SELECT COUNT(*) as total
                FROM instagram_interact
                WHERE instagram_username_interact = ?
                  AND target = ?
                  AND followed = 1
                  AND job_name = 'blogger-post-likers'
            ";
            $totalStmt = $this->db->prepare($totalSql);
            $totalStmt->bind_param("ss", $instagram_username_interact, $target);
            $totalStmt->execute();
            $totalRow = $totalStmt->get_result()->fetch_assoc();
            $totalStmt->close();
            $totalFollows = (int)($totalRow['total'] ?? 0);

            if ($totalFollows === 0) {
                echo "Skipped (no follows recorded): $target\n";
                continue;
            }

            $backSql = "
                SELECT COUNT(*) as backs
                FROM instagram_notifications
                WHERE instagram_username_interact = ?
                  AND target = ?
                  AND is_follow_back = 1
            ";
            $backStmt = $this->db->prepare($backSql);
            $backStmt->bind_param("ss", $instagram_username_interact, $target);
            $backStmt->execute();
            $backRow = $backStmt->get_result()->fetch_assoc();
            $backStmt->close();
            $followBacks = (int)($backRow['backs'] ?? 0);

            $rate = $totalFollows > 0 ? $followBacks / $totalFollows : 0;

            if ($rate <= 0.1) {
                echo "Skipped (follow-back rate too low: " . round($rate * 100, 1) . "%): $target\n";
                continue;
            }

            echo "Target $target — follow-back rate: " . round($rate * 100, 1) . "%\n";

            // Step 5: Cross-job dedup
            $dedupSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_add_config
                WHERE instagram_username_interact = ?
                  AND instagram_username = ?
            ";
            $dedupStmt = $this->db->prepare($dedupSql);
            $dedupStmt->bind_param("ss", $instagram_username_interact, $target);
            $dedupStmt->execute();
            $dedupRow = $dedupStmt->get_result()->fetch_assoc();
            $dedupStmt->close();

            if (($dedupRow['cnt'] ?? 0) > 0) {
                echo "Skipped (exists in another job): $target\n";
                continue;
            }

            // Step 6: Insert
            $insertSql = "
                INSERT INTO instagram_add_config
                    (instagram_username_interact, instagram_username, job_name, is_added)
                VALUES (?, ?, 'blogger-post-likers', 1)
            ";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->bind_param("ss", $instagram_username_interact, $target);
            $insertStmt->execute();
            $insertStmt->close();

            echo "Inserted: $instagram_username_interact / $target\n";
            $insertCount++;
        }

        echo "updateConfigLikerSession completed. Inserted: $insertCount.\n";
    }

    /**
     * Update config followers based on instagram_session.blogger_followers.
     * Only considers sessions with successful_interactions > 10.
     * Only adds targets with follow-back rate > 10% not used in the last 2 days.
     *
     * @param string $instagram_username_interact
     */
    public function updateConfigFollowerSession($instagram_username_interact)
    {
        $dateMinus2 = date('Y-m-d', strtotime('-2 days'));

        // Step 1: Fetch sessions from last 2 days with successful_interactions > 10
        $sessionSql = "
            SELECT session_id, blogger_followers
            FROM instagram_session
            WHERE instagram_username_interact = ?
              AND successful_interactions > 10
              AND blogger_followers IS NOT NULL
              AND blogger_followers != 'null'
        ";
        $sessionStmt = $this->db->prepare($sessionSql);
        $sessionStmt->bind_param("s", $instagram_username_interact);
        $sessionStmt->execute();
        $sessionResult = $sessionStmt->get_result();

        // Step 2: Build unique target list from JSON columns
        $targets = [];
        while ($row = $sessionResult->fetch_assoc()) {
            $decoded = json_decode($row['blogger_followers'], true);
            // Handle double-encoded JSON
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            if (!is_array($decoded)) {
                continue;
            }
            foreach ($decoded as $target) {
                $targets[$target] = true;
            }
        }
        $sessionStmt->close();

        if (empty($targets)) {
            echo "No targets found in recent sessions.\n";
            return;
        }

        $targets = array_keys($targets);
        echo "Targets from sessions: " . implode(', ', $targets) . "\n";

        $insertCount = 0;

        foreach ($targets as $target) {

            // Step 3: Recency guard — skip if used in last 2 days
            $recentSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_interact
                WHERE target = ?
                  AND instagram_username_interact = ?
                  AND date_created > ?
            ";
            $recentStmt = $this->db->prepare($recentSql);
            $recentStmt->bind_param("sss", $target, $instagram_username_interact, $dateMinus2);
            $recentStmt->execute();
            $recentRow = $recentStmt->get_result()->fetch_assoc();
            $recentStmt->close();

            if (($recentRow['cnt'] ?? 0) > 0) {
                echo "Skipped (used in last 2 days): $target\n";
                continue;
            }

            // Step 4: Calculate follow-back rate for this target
            $totalSql = "
                SELECT COUNT(*) as total
                FROM instagram_interact
                WHERE instagram_username_interact = ?
                  AND target = ?
                  AND followed = 1
                  AND job_name = 'blogger-followers'
            ";
            $totalStmt = $this->db->prepare($totalSql);
            $totalStmt->bind_param("ss", $instagram_username_interact, $target);
            $totalStmt->execute();
            $totalRow = $totalStmt->get_result()->fetch_assoc();
            $totalStmt->close();
            $totalFollows = (int)($totalRow['total'] ?? 0);

            if ($totalFollows === 0) {
                echo "Skipped (no follows recorded): $target\n";
                continue;
            }

            $backSql = "
                SELECT COUNT(*) as backs
                FROM instagram_notifications
                WHERE instagram_username_interact = ?
                  AND target = ?
                  AND is_follow_back = 1
            ";
            $backStmt = $this->db->prepare($backSql);
            $backStmt->bind_param("ss", $instagram_username_interact, $target);
            $backStmt->execute();
            $backRow = $backStmt->get_result()->fetch_assoc();
            $backStmt->close();
            $followBacks = (int)($backRow['backs'] ?? 0);

            $rate = $totalFollows > 0 ? $followBacks / $totalFollows : 0;

            if ($rate <= 0.1) {
                echo "Skipped (follow-back rate too low: " . round($rate * 100, 1) . "%): $target\n";
                continue;
            }

            echo "Target $target — follow-back rate: " . round($rate * 100, 1) . "%\n";

            // Step 5: Cross-job dedup
            $dedupSql = "
                SELECT COUNT(*) as cnt
                FROM instagram_add_config
                WHERE instagram_username_interact = ?
                  AND instagram_username = ?
            ";
            $dedupStmt = $this->db->prepare($dedupSql);
            $dedupStmt->bind_param("ss", $instagram_username_interact, $target);
            $dedupStmt->execute();
            $dedupRow = $dedupStmt->get_result()->fetch_assoc();
            $dedupStmt->close();

            if (($dedupRow['cnt'] ?? 0) > 0) {
                echo "Skipped (exists in another job): $target\n";
                continue;
            }

            // Step 6: Insert
            $insertSql = "
                INSERT INTO instagram_add_config
                    (instagram_username_interact, instagram_username, job_name, is_added)
                VALUES (?, ?, 'blogger-followers', 1)
            ";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->bind_param("ss", $instagram_username_interact, $target);
            $insertStmt->execute();
            $insertStmt->close();

            echo "Inserted: $instagram_username_interact / $target\n";
            $insertCount++;
        }

        echo "updateConfigFollowerSession completed. Inserted: $insertCount.\n";
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
    $updater->updateConfigLikerSession($instagram_username_interact);
    $updater->updateConfigFollowerSession($instagram_username_interact);
    #$updater->updateRemoveConfig($instagram_username_interact,$datee);

    echo "Update completed.\n";
}
?>


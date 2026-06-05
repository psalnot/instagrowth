<?php
require_once("../utils/defines.php");
require_once("../class/mysql.class.php");


function calculateMinimalSampleSize($populationSize, $zScore, $marginOfError, $estimatedProportion = 0.5) {
    // Ensure proportion is between 0 and 1
    $p = max(0, min(1, $estimatedProportion));
    
    // Calculate q (1-p)
    $q = 1 - $p;
    
    // Convert margin of error to decimal
    $e = $marginOfError / 100;
    
    // Calculate numerator and denominator
    $numerator = ($zScore * $zScore) * $p * $q * $populationSize;
    $denominator = (($marginOfError * $marginOfError) * ($populationSize - 1)) + (($zScore * $zScore) * $p * $q);
    
    // Calculate and round up to the nearest integer
    $sampleSize = ceil($numerator / $denominator);
    
    return $sampleSize;
}


/**
 * Retrieves a random sample of Instagram users for follow-back testing
 * 
 * Selects N random users from the instagram_interact table that:
 * - Haven't been tested for follow-back (is_follow_back_test = 0)
 * - Have no skip reason (skip_reason IS NULL)
 * - Match the specified username (instagram_username_interact)
 *
 * @param mysqli $cstring Database connection object
 * @param string $username The Instagram username to filter interactions for
 * @param int $n The number of random users to select
 * @return array|null Returns array of users with their details or null on error
 * @throws Exception If database query fails
 */
function getRandomUsersForFollowBackTest(mysqli $cstring, string $username, int $n): ?array {
    try {
        $query = "SELECT 
                instagram_username,
                job_name,
                target
            FROM instagram_interact 
            WHERE is_follow_back_test = 0 
            AND skip_reason IS NULL 
	    AND instagram_username_interact = ?
	    AND (followed=1 or liked=1)
            ORDER BY RAND() 
            LIMIT ?";
            
        $stmt = $cstring->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $cstring->error);
        }
        
        $stmt->bind_param("si", $username, $n);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        $stmt->close();
        
        return $users;
        
    } catch (Exception $e) {
        error_log("Error in getRandomUsersForFollowBackTest: " . $e->getMessage());
        return null;
    }
}

/**
 * Counts eligible Instagram interactions for a specific username
 * 
 * This function counts the number of records in instagram_interact table that:
 * - Match the specified username (instagram_username_interact)
 * - Have no skip reason (skip_reason IS NULL)
 *
 * @param mysqli $cstring Database connection object
 * @param string $username The Instagram username to count interactions for
 * @return int|null Returns the count of eligible interactions or null on error
 * @throws Exception If database query fails
 */
function countEligibleInteractions(mysqli $cstring, string $username): ?int {
    try {
        $query = "SELECT COUNT(*) as total 
                FROM instagram_interact 
                WHERE instagram_username_interact = ? 
		AND skip_reason IS NULL and (followed=1 or liked=1)";
        
        $stmt = $cstring->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $cstring->error);
        }
        
        $stmt->bind_param("s", $username);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute query: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        
        return (int)$row['total'];
        
    } catch (Exception $e) {
        error_log("Error in countEligibleInteractions: " . $e->getMessage());
        return null;
    }
}

/**
 * Inserts users into the instagram_random_followback table
 * 
 * This function attempts to insert users into the instagram_random_followback table.
 * If a user already exists in the table, it skips that user and continues with the next one.
 * Returns the number of new users successfully inserted.
 *
 * @param mysqli $cstring Database connection object
 * @param array $users Array of users to insert
 * @param string $username The Instagram username to interact with
 * @return int Returns the number of users successfully inserted
 * @throws Exception If database query fails
 */
function insertUsersIntoFollowBackTable(mysqli $cstring, array $users, string $username): int {
    try {

        // First check for any unprocessed entries
        $checkUnprocessedQuery = "SELECT COUNT(*) as count 
                                FROM instagram_random_followback 
                                WHERE instagram_username_interact = ? 
                                AND is_processed = 0";
        
        $checkStmt = $cstring->prepare($checkUnprocessedQuery);
        if (!$checkStmt) {
            throw new Exception("Failed to prepare unprocessed check statement: " . $cstring->error);
        }
        
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();
        $checkStmt->close();
        
        if ($row['count'] > 0) {
            error_log("Warning: Unprocessed entries exist for username: $username. Skipping new insertions.");
            return 0;
        }


        $insertCount = 0;
        $insertQuery = "INSERT INTO instagram_random_followback 
                       (instagram_username, instagram_username_interact, job_name, target, date_created) 
                       VALUES (?, ?, ?, ?, NOW())";
        
        $checkQuery = "SELECT COUNT(*) as count 
                      FROM instagram_random_followback 
                      WHERE instagram_username = ? 
                      AND instagram_username_interact = ?";
        
        // Prepare statements once outside the loop
        $checkStmt = $cstring->prepare($checkQuery);
        $insertStmt = $cstring->prepare($insertQuery);
        
        if (!$checkStmt || !$insertStmt) {
            throw new Exception("Failed to prepare statements: " . $cstring->error);
        }
        
        foreach ($users as $user) {
            // Check if user already exists
            $checkStmt->bind_param("ss", $user['instagram_username'], $username);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                error_log("Skipping duplicate entry for username: {$user['instagram_username']}");
                continue; // Skip to next user if already exists
            }
            
            // Insert new user
            $insertStmt->bind_param("ssss", 
                $user['instagram_username'], 
                $username, 
                $user['job_name'], 
                $user['target']
            );
            
            if ($insertStmt->execute()) {
                $insertCount++;
            } else {
                error_log("Failed to insert user {$user['instagram_username']}: " . $insertStmt->error);
            }
        }
        
        // Clean up
        $checkStmt->close();
        $insertStmt->close();
        
        return $insertCount;
        
    } catch (Exception $e) {
        error_log("Error in insertUsersIntoFollowBackTable: " . $e->getMessage());
        return 0;
    }
}



// Example usage:
/*
$numberOfUsers = 100;
$username = "example_instagram_account";
$users = getRandomUsersForFollowBackTest($mysqli, $username, $numberOfUsers);

if ($users !== null) {
    foreach ($users as $user) {
        echo "Username: " . $user['instagram_username'] . "\n";
        echo "Job: " . $user['job_name'] . "\n";
        echo "Target: " . $user['target'] . "\n";
    }
}
*/

// Check if a username was passed as an argument
if ($argc < 2) {
    echo "Usage: php  program_name <username>\n";
    exit(1);
}

// Get the username from the command line argument
$username = $argv[1];

try {
    $msql = new MMsql();
    $cstring = $msql->dbconnect($DBUSER, $DBPASS, $DBNAME);
} catch (Exception $e) {
    error_log("Error in dbconnect: " . $e->getMessage());
    exit;
}




//Retrieve random users for follow-back test
try {
    $count = countEligibleInteractions($cstring, $username);
    // Example usage:
    // Z-score of 1.96 represents 95% confidence level
    // Margin of error of 15%
    $sampleSize = calculateMinimalSampleSize($count, 1.96, 0.15, 0.5);
    echo " Count number " . $count . " Sample size " . $sampleSize . "  \n";
    $users = getRandomUsersForFollowBackTest($cstring , $username , $sampleSize);
    $insertedCount = insertUsersIntoFollowBackTable($cstring, $users, $username);
    //mysqli_close($cstring);
} catch (Exception $e) {
    error_log("Error in getRandomUsersForFollowBackTest: " . $e->getMessage());
}
//Display users for debug purpose
/*
if ($users !== null) {
    foreach ($users as $user) {
        echo "Username: " . $user['instagram_username'] . "\n";
        echo "Job: " . $user['job_name'] . "\n";
        echo "Target: " . $user['target'] . "\n";
    }
}
*/
mysqli_close($cstring);

?>

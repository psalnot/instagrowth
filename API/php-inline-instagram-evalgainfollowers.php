<?php

require_once("../utils/defines.php");
require_once("../class/instagrowth.class.php");
require_once("../class/mysql.class.php");


// Check if a username was passed as an argument
if ($argc < 2) {
    echo "Usage: php  program_name <username>\n";
    exit(1);
}

// Get the username from the command line argument
$username = $argv[1];

// Create a new MSQL instance 
try {
    $msql = new MMsql();
    $cstring = $msql->dbconnect($DBUSER, $DBPASS, $DBNAME);

    $instagrowth = new MInstagrowth();
    $followers = $instagrowth->evalGainFollowers($cstring,$username);
    #$followers=-1;
    $targets = $instagrowth->getTargetList($cstring, $username,1.3);
    if ($targets) {
        foreach ($targets as $target) {
            #echo "Username: " . $target['instagram_username'] . "\n";
            #echo "Followers: " . $target['followers'] . "\n";
            #echo "Job Name: " . $target['job_name'] . "\n";
            #echo "Target: " . $target['target'] . "\n";
            #echo "Liked: " . $target['liked'] . "\n";
            #echo "Watched: " . $target['watched'] . "\n";
            #echo "Following: " . $target['following'] . "\n";
            #echo "Mutual Friends: " . $target['mutual_friends'] . "\n";
            #echo "Bio: " . $target['biography'] . "\n";
            #echo "Potency Ratio: " . $target['potency_ratio'] . "\n";
            #echo "Last Interaction: " . $target['last_interaction'] . "\n";
            echo "ICI";
            $instagrowth->setToValidateTargetList($cstring, $username, $target);
            #$instagrowth->updateAddConfigTargetList($cstring, $username, $target['target'], $target['job_name']);
            echo "------------------------\n";
        }
    }
    
    if ($followers != -1)
    {

        $gain =  $followers['current'] - $followers['previous'];
        echo "Gain followers for '$username' : " . $gain . "\n";
        echo "Current session id : " . $followers['current_session_id'] . "\n";
        echo "Previous session id : " . $followers['previous_session_id'] . "\n";
        // Retrieve list of targets for the current session
        $list_targets = $instagrowth->getListTargetJob($cstring, $followers['current_session_id']);
        foreach ($list_targets as $target) {
            echo "Target : " . $target['target'] . "\n";
            echo "Job name : " . $target['job_name'] . "\n";
            $target_info = $instagrowth->buildTargetInfo($cstring, $target['target'], $followers['current_session_id'], $target['job_name']);
            echo "Target failure rate : " . $target_info['failure_rate'] . "\n";
            echo "Target total interactions :   " . $target_info['total_interactions'] . "\n";
            echo "Target total likes : " . $target_info['total_likes'] . "\n";
            #echo "Target total comments : " . $target_info['total_comments'] . "\n";
            echo "Target total watched : " . $target_info['total_watched'] . "\n";
            // Check if the target should be removed
            if ($instagrowth->checkRemoveTarget($target['target'], $target_info, $gain, 13)) {
                echo "Target should be removed for '$username'\n";
                $instagrowth->logRemoveTarget($cstring, $target, $target_info, $followers['current_session_id'], $username);
                #$instagrowth->updateRemoveConfigAddTargetList($cstring, $username, $target['target'], $target['job_name']);

            }
            else
            {
                echo "Target should not be removed for '$username'\n";
            }
        }
    }
    else
    {
        echo "Error evalGainFollowers for '$username' \n";
    }

    

    #echo "Top followed targets for '$username' have been inserted successfully.\n";

} catch (Exception $e) {
    echo "Database error: " . mysqli_error($cstring) . "\n";
    exit(1);
    
}

mysqli_close($cstring);


?>

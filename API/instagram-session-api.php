<?php
// insert_instagram_session.php

// Database connection settings
$host = 'localhost';

require_once("../utils/defines.php");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$DBNAME;charset=utf8", $DBUSER, $DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Authentication key
$auth_key = '4242';

// Extract JSON data from the request body
$data = json_decode(file_get_contents('php://input'), true);
//error_log(print_r($data, true));

// Check if JSON was decoded correctly
if ($data === null) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON data']);
    exit;
}

$remote_host = $_SERVER['REMOTE_ADDR']; // Get the remote host IP address

// Check for API key
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $received_key = $data['key'] ?? null;

    if ($received_key !== $auth_key) {
        http_response_code(403);
        echo json_encode(['message' => 'Forbidden: Invalid API Key']);
        exit;
    }

    if (isset($data['session_id'])) {
        $session_id = $data['session_id'];
        
        // Prepare a statement to check if the session_id exists
        $check_query = "SELECT COUNT(*) FROM instagram_session WHERE session_id = ?";
        $stmt = $pdo->prepare($check_query); // Prepare the statement here
        // Bind the parameter using bindValue
        $stmt->bindValue(1, $session_id, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch the count directly
        $count = $stmt->fetchColumn();


        if ($count > 0)
        {
            // Session ID already exists
            http_response_code(409); // Conflict status code
            echo json_encode(['message' => 'Error: session_id already exists.']);
            exit();
        }
    }
    #profile_posts, profile_followers, profile_following
    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO instagram_session 
        (instagram_username_interact, profile_posts, profile_followers, profile_following, session_id, total_interactions, successful_interactions, total_followed, total_likes, total_comments, total_pm, total_watched, total_unfollowed,
        start_time, finish_time, blogger, interact_from_file, unfollow_from_file, blogger_followers, blogger_following,
        blogger_post_likers, blogger_post_limits, feed, hashtag_likers_top, hashtag_likers_recent,
        hashtag_posts_recent, hashtag_posts_top, place_likers_top, place_likers_recent, place_posts_recent,
        place_posts_top, posts_from_file, remove_followers_from_file, delete_removed_followers, telegram_reports,
        time_delta_session, current_likes_limit, current_follow_limit, current_unfollow_limit, current_comments_limit,
        current_pm_limit, current_watch_limit, current_success_limit, current_total_limit, current_scraped_limit,
        current_crashes_limit, date_created, date_updated, remote_host) 
        VALUES 
        (:instagram_username_interact, :profile_posts, :profile_followers, :profile_following, :session_id, :total_interactions, :successful_interactions, :total_followed, :total_likes, :total_comments,
        :total_pm, :total_watched, :total_unfollowed, :start_time, :finish_time, :blogger, :interact_from_file,
        :unfollow_from_file, :blogger_followers, :blogger_following, :blogger_post_likers, :blogger_post_limits,
        :feed, :hashtag_likers_top, :hashtag_likers_recent, :hashtag_posts_recent, :hashtag_posts_top,
        :place_likers_top, :place_likers_recent, :place_posts_recent, :place_posts_top, :posts_from_file,
        :remove_followers_from_file, :delete_removed_followers, :telegram_reports, :time_delta_session,
        :current_likes_limit, :current_follow_limit, :current_unfollow_limit, :current_comments_limit,
        :current_pm_limit, :current_watch_limit, :current_success_limit, :current_total_limit,
        :current_scraped_limit, :current_crashes_limit, NOW(), NOW(), :remote_host)");

    // Assign values to variables before binding (required for functions like json_encode)
    $blogger = json_encode($data['blogger'] ?? []);
    $interact_from_file = json_encode($data['interact_from_file'] ?? [] );
    #Add profile informations
    $profile_posts = $data['profile_posts'] ?? null; 
    $profile_followers = $data['profile_followers'] ?? null; 
    $profile_following = $data['profile_following'] ?? null;
    #Retrieve start_time and finish_time
    $start_time = $data['start_time'] ?? null; 
    $finish_time = $data['finish_time'] ?? null; 

    $unfollow_from_file = json_encode($data['unfollow_from_file'] ?? [] );
    $blogger_followers = json_encode($data['blogger_followers'] ?? [] );
    $blogger_following = json_encode($data['blogger_following'] ?? []);
    $blogger_post_likers = json_encode($data['blogger_post_likers'] ?? [] );
    $hashtag_likers_top = json_encode($data['hashtag_likers_top'] ?? []);
    $hashtag_likers_recent = json_encode($data['hashtag_likers_recent'] ?? [] );
    $hashtag_posts_recent = json_encode($data['hashtag_posts_recent'] ?? [] );
    $hashtag_posts_top = json_encode($data['hashtag_posts_top'] ?? [] );
    $place_likers_top = json_encode($data['place_likers_top'] ?? []);
    $place_likers_recent = json_encode($data['place_likers_recent']?? []);
    $place_posts_recent = json_encode($data['place_posts_recent'] ?? [] );
    $place_posts_top = json_encode($data['place_posts_top'] ?? []);
    $posts_from_file = json_encode($data['posts_from_file'] ?? []);
    $remove_followers_from_file = json_encode($data['remove_followers_from_file'] ?? []);
    $delete_removed_followers = isset($data['delete_removed_followers']) && $data['delete_removed_followers'] !== '' 
                            ? (int)$data['delete_removed_followers'] 
                            : 0; // default to 0
    $telegram_reports = isset($data['telegram_reports']) && $data['telegram_reports'] !== '' 
                            ? (int)$data['telegram_reports'] 
                            : 0; // default to 0 if empty or missing

    
    // Bind parameters
    $stmt->bindParam(':instagram_username_interact', $data['instagram_username_interact']);
    $stmt->bindParam(':profile_posts', $profile_posts);
    $stmt->bindParam(':profile_followers', $profile_followers);
    $stmt->bindParam(':profile_following', $profile_following);



    $stmt->bindParam(':session_id', $data['session_id']);
    $stmt->bindParam(':total_interactions', $data['total_interactions']);
    $stmt->bindParam(':successful_interactions', $data['successful_interactions']);
    $stmt->bindParam(':total_followed', $data['total_followed']);
    $stmt->bindParam(':total_likes', $data['total_likes']);
    $stmt->bindParam(':total_comments', $data['total_comments']);
    $stmt->bindParam(':total_pm', $data['total_pm']);
    $stmt->bindParam(':total_watched', $data['total_watched']);
    $stmt->bindParam(':total_unfollowed', $data['total_unfollowed']);
    #$stmt->bindParam(':start_time', $data['start_time']);

    

    if ($start_time === null) {
        $stmt->bindValue(':start_time', null, PDO::PARAM_NULL); // Bind as NULL
    } else {
        $stmt->bindParam(':start_time', $start_time); // Bind the actual value if not null
    }
    $stmt->bindParam(':finish_time', $finish_time, PDO::PARAM_NULL); // Use PDO::PARAM_NULL to bind null values
    
    


    #$stmt->bindParam(':finish_time', $data['finish_time']);
    $stmt->bindParam(':blogger', $blogger); // JSON encoded
    $stmt->bindParam(':interact_from_file', $interact_from_file); // JSON encoded
    $stmt->bindParam(':unfollow_from_file', $unfollow_from_file); // JSON encoded
    $stmt->bindParam(':blogger_followers', $blogger_followers); // JSON encoded
    $stmt->bindParam(':blogger_following', $blogger_following); // JSON encoded
    $stmt->bindParam(':blogger_post_likers', $blogger_post_likers); // JSON encoded
    $stmt->bindParam(':blogger_post_limits', $data['blogger_post_limits']);
    $stmt->bindParam(':feed', $data['feed']);
    $stmt->bindParam(':hashtag_likers_top', $hashtag_likers_top); // JSON encoded
    $stmt->bindParam(':hashtag_likers_recent', $hashtag_likers_recent); // JSON encoded
    $stmt->bindParam(':hashtag_posts_recent', $hashtag_posts_recent); // JSON encoded
    $stmt->bindParam(':hashtag_posts_top', $hashtag_posts_top); // JSON encoded
    $stmt->bindParam(':place_likers_top', $place_likers_top); // JSON encoded
    $stmt->bindParam(':place_likers_recent', $place_likers_recent); // JSON encoded
    $stmt->bindParam(':place_posts_recent', $place_posts_recent); // JSON encoded
    $stmt->bindParam(':place_posts_top', $place_posts_top); // JSON encoded
    $stmt->bindParam(':posts_from_file', $posts_from_file); // JSON encoded
    $stmt->bindParam(':remove_followers_from_file', $remove_followers_from_file); // JSON encoded

    $stmt->bindParam(':delete_removed_followers', $delete_removed_followers);
    $stmt->bindParam(':telegram_reports', $telegram_reports);
    $stmt->bindParam(':time_delta_session', $data['time_delta_session']);
    $stmt->bindParam(':current_likes_limit', $data['current_likes_limit']);
    $stmt->bindParam(':current_follow_limit', $data['current_follow_limit']);
    $stmt->bindParam(':current_unfollow_limit', $data['current_unfollow_limit']);
    $stmt->bindParam(':current_comments_limit', $data['current_comments_limit']);
    $stmt->bindParam(':current_pm_limit', $data['current_pm_limit']);
    $stmt->bindParam(':current_watch_limit', $data['current_watch_limit']);
    $stmt->bindParam(':current_success_limit', $data['current_success_limit']);
    $stmt->bindParam(':current_total_limit', $data['current_total_limit']);
    $stmt->bindParam(':current_scraped_limit', $data['current_scraped_limit']);
    $stmt->bindParam(':current_crashes_limit', $data['current_crashes_limit']);
    $stmt->bindParam(':remote_host', $remote_host); // Bind remote host

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Data inserted successfully.']);
    } else {
        echo json_encode(['message' => 'Data insertion failed.']);
    }

} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>

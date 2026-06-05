<?php
// Database configuration
$host = 'localhost';
#$dbname = 'your_database_name';
#$username = 'your_db_username';
#$password = 'your_db_password';

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../utils/defines.php");



// Create a connection to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$DBNAME;charset=utf8", $DBUSER, $DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Authentication key (for simplicity, using a static key)
$valid_key = '4242';  // The key required to process the request

// Function to create or update an entry
function handleInteraction($pdo, $valid_key)
{
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if the 'key' parameter is provided and valid
    if (!isset($input['key']) || $input['key'] !== $valid_key) {
        echo json_encode(['error' => 'Invalid or missing authentication key.']);
        http_response_code(403);
        return;
    }

    // Check if 'instagram_username' exists
    if (!isset($input['instagram_username'])) {
        echo json_encode(['error' => 'Instagram username is required.']);
        http_response_code(400);
        return;
    }

    $instagram_username = $input['instagram_username'];

    // Check if the instagram_username is for an existing entry
    $stmt = $pdo->prepare("SELECT * FROM instagram_interactions WHERE instagram_username = :instagram_username");
    $stmt->execute(['instagram_username' => $instagram_username]);
    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    // Prepare data to be inserted or updated
    $data = [
        'instagram_username_interact' => $input['instagram_username_interact'] ?? null,
        'follow_back' => $input['follow_back'] ?? false,
        'followed' => $input['followed'] ?? false,
        'like_count' => $input['like_count'] ?? 0,
        'unfollowed' => $input['unfollowed'] ?? false,
        'story_view_count' => $input['story_view_count'] ?? 0,
        'reel_view_count' => $input['reel_view_count'] ?? 0,
        'target' => $input['target'] ?? null,
        'last_interaction' => $input['last_interaction'] ?? null,
        'followers' => $input['followers'] ?? null,
        'following' => $input['following'] ?? null,
        'session_id' => $input['session_id'] ?? null,
        'job_name' => $input['job_name'] ?? null,
        'instagram_username' => $instagram_username
    ];

    // If record exists, update it
    if ($existingRecord) {
        $updateSQL = "UPDATE instagram_interactions 
                      SET instagram_username_interact = :instagram_username_interact, follow_back = :follow_back, followed = :followed, 
                          like_count = :like_count, unfollowed = :unfollowed, story_view_count = :story_view_count, reel_view_count = :reel_view_count, 
                          target = :target, last_interaction = :last_interaction, followers = :followers, following = :following, 
                          session_id = :session_id, job_name = :job_name, date_updated = NOW()
                      WHERE instagram_username = :instagram_username";

        $stmt = $pdo->prepare($updateSQL);
        $stmt->execute($data);
        echo json_encode(['message' => 'Record updated successfully.']);

    } else {
        // Insert new record if it doesn't exist
        $insertSQL = "INSERT INTO instagram_interactions 
                      (instagram_username_interact, follow_back, followed, like_count, unfollowed, story_view_count, reel_view_count, target, 
                       last_interaction, instagram_username, followers, following, session_id, job_name, date_created)
                      VALUES (:instagram_username_interact, :follow_back, :followed, :like_count, :unfollowed, :story_view_count, 
                              :reel_view_count, :target, :last_interaction, :instagram_username, :followers, :following, :session_id, 
                              :job_name, NOW())";

        $stmt = $pdo->prepare($insertSQL);
        $stmt->execute($data);
        echo json_encode(['message' => 'Record created successfully.']);
    }
}

// Handle the request based on the HTTP method
switch ($requestMethod) {
    case 'POST':
    case 'PUT':
        handleInteraction($pdo, $valid_key);
        break;

    default:
        echo json_encode(['error' => 'Invalid request method.']);
        http_response_code(405);
        break;
}
?>

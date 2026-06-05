<?php

require_once("../utils/defines.php");

class InstagramInteractApi {
    private $pdo;
    private $auth_key;

    public function __construct($host, $db, $user, $pass, $auth_key) {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new Exception('Database connection error: ' . $e->getMessage());
        }

        // Set the expected authentication key
        $this->auth_key = $auth_key;
    }

    /**
     * Verifies if the provided authentication key is valid.
     *
     * @param string $provided_key The key sent by the client.
     * @return bool True if the key is valid, false otherwise.
     */
    public function verifyAuthKey(string $provided_key): bool {
        return $this->auth_key === $provided_key;
    }

    /**
     * Inserts a new record into the instagram_interact table.
     *
     * @param array $data An associative array of data to insert.
     * @param string $remoteHost The IP address of the remote host.
     * @return void
     * @throws Exception if the insertion fails.
     */
    public function insertData(array $data, string $remoteHost) {
        $sql = "INSERT INTO instagram_interact (
                    instagram_username,
                    instagram_username_interact,
                    last_interaction,
                    following_status,
                    session_id,
                    job_name,
                    target,
                    liked,
                    watched,
                    commented,
                    followed,
                    unfollowed,
                    scraped,
                    pm_sent,
                    followers,
                    following,
                    mutual_friends,
                    follow_button_text,
                    biography,
                    has_business_category,
                    posts_count,
                    fullname,
                    link_in_bio,
                    potency_ratio,
                    skip_reason,
                    date_created,
                    date_updated,
                    remote_host
                ) VALUES (
                    :instagram_username,
                    :instagram_username_interact,
                    :last_interaction,
                    :following_status,
                    :session_id,
                    :job_name,
                    :target,
                    :liked,
                    :watched,
                    :commented,
                    :followed,
                    :unfollowed,
                    :scraped,
                    :pm_sent,
                    :followers,
                    :following,
                    :mutual_friends,
                    :follow_button_text,
                    :biography,
                    :has_business_category,
                    :posts_count,
                    :fullname,
                    :link_in_bio,
                    :potency_ratio,
                    :skip_reason,
                    NOW(),
                    NOW(),
                    :remote_host
                )";

        $stmt = $this->pdo->prepare($sql);

        // Ensure all required keys are present in $data and set to null if missing
        $required_keys = [
            'instagram_username', 'instagram_username_interact', 'last_interaction', 'following_status',
            'session_id', 'job_name', 'target', 'liked', 'watched', 'commented', 'followed', 'unfollowed',
            'scraped', 'pm_sent', 'followers', 'following', 'mutual_friends', 'follow_button_text',
            'biography', 'has_business_category', 'posts_count', 'fullname', 'link_in_bio',
            'potency_ratio', 'skip_reason'
        ];

        // Loop through each required key and set it to null if missing
        foreach ($required_keys as $key) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = null;
            }
        }

        // Add the remote host to the data
        $data['remote_host'] = $remoteHost;

        // Execute the query
        if (!$stmt->execute($data)) {
            throw new Exception('Failed to insert data into the database.');
        }
    }
}

// REST API Endpoint
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Method Not Allowed']);
    exit;
}

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate the input
if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Invalid JSON input.']);
    exit;
}

// Ensure the 'auth_key' is provided
if (!isset($input['auth_key'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Authentication key missing.']);
    exit;
}

// Expected authentication key (for example, from environment variables or configuration)
$expected_auth_key = '4242'; // Replace with your actual authentication key

try {
    // Create API instance
    $api = new InstagramInteractApi('localhost', $DBNAME, $DBUSER, $DBPASS, $expected_auth_key);
    
    // Verify the provided authentication key
    if (!$api->verifyAuthKey($input['auth_key'])) {
        http_response_code(403); // Forbidden
        echo json_encode(['message' => 'Invalid authentication key.']);
        exit;
    }

    // Remove the auth_key from input data before insertion
    unset($input['auth_key']);

    // Retrieve the remote host IP address
    $remoteHost = $_SERVER['REMOTE_ADDR'];

    // Insert data into the database
    $api->insertData($input, $remoteHost);

    // Return success response
    http_response_code(201); // Created
    echo json_encode(['message' => 'Data inserted successfully.']);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => $e->getMessage()]);
}

?>

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
     * Checks if the Instagram username already exists in the table.
     *
     * @param string $instagram_username The Instagram username to check.
     * @return bool True if the username exists, false otherwise.
     */
    public function usernameExists(string $instagram_username, string $session_id): bool {
        // Validate inputs
        if (empty($instagram_username) || empty($session_id)) {
            return false;
        }

        $sql = "SELECT COUNT(*) FROM instagram_interact WHERE instagram_username = :instagram_username and session_id= :session_id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['instagram_username' => $instagram_username, 'session_id' => $session_id ]);
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            // Log the exception or handle it as needed
            return false; // or rethrow, depending on how you want to handle errors
        }
    }

    /**
     * Inserts or updates a record in the instagram_interact table.
     *
     * @param array $data An associative array of data to insert or update.
     * @param string $remoteHost The IP address of the remote host.
     * @return void
     * @throws Exception if the insertion or update fails.
     */
    public function insertOrUpdateData(array $data, string $remoteHost) {
        if ($this->usernameExists($data['instagram_username'], $data['session_id'])) {
            // Update existing record
            $sql = "UPDATE instagram_interact SET
                        instagram_username_interact = :instagram_username_interact,
                        last_interaction = :last_interaction,
                        following_status = :following_status,
                        job_name = :job_name,
                        target = :target,
                        liked = :liked,
                        watched = :watched,
                        commented = :commented,
                        followed = :followed,
                        unfollowed = :unfollowed,
                        scraped = :scraped,
                        pm_sent = :pm_sent,
                        followers = :followers,
                        following = :following,
                        mutual_friends = :mutual_friends,
                        follow_button_text = :follow_button_text,
                        biography = :biography,
                        has_business_category = :has_business_category,
                        posts_count = :posts_count,
                        fullname = :fullname,
                        link_in_bio = :link_in_bio,
                        potency_ratio = :potency_ratio,
                        skip_reason = :skip_reason,
                        date_updated = NOW(),
                        remote_host = :remote_host
                    WHERE instagram_username = :instagram_username and session_id = :session_id ";
        } else {
            // Insert new record
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
        }

        $stmt = $this->pdo->prepare($sql);

        // Ensure all required keys are present in $data and set to null if missing
        $required_keys = [
            'instagram_username', 'instagram_username_interact', 'last_interaction', 'following_status',
            'session_id', 'job_name', 'target', 'liked', 'watched', 'commented', 'followed', 'unfollowed',
            'scraped', 'pm_sent', 'followers', 'following', 'mutual_friends', 'follow_button_text',
            'biography', 'has_business_category', 'posts_count', 'fullname', 'link_in_bio',
            'potency_ratio', 'skip_reason'
        ];

        foreach ($required_keys as $key) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = null;
            }
        }

        
        if (isset($data['unfollowed']) && ($data['unfollowed'] === true || $data['unfollowed'] == 1)) {
            $data['followed'] = 1;

        }
        error_log("SQL Query: $sql");
        error_log("Parameters: " . json_encode($data));

        #if (!isset($data["sesssion_id"])){
         #   $data["session_id"] = "4242";
        #}

        $data['remote_host'] = $remoteHost;

        if (!$stmt->execute($data)) {
            throw new Exception('Failed to insert or update data in the database.');
        }
    }
}

// REST API Endpoint
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON input.']);
    exit;
}

if (!isset($input['auth_key'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Authentication key missing.']);
    exit;
}

$expected_auth_key = '4242';  // Replace with actual auth key

try {
    $api = new InstagramInteractApi('localhost', $DBNAME, $DBUSER, $DBPASS, $expected_auth_key);
    
    if (!$api->verifyAuthKey($input['auth_key'])) {
        http_response_code(403);
        echo json_encode(['message' => 'Invalid authentication key.']);
        exit;
    }

    unset($input['auth_key']);
    $remoteHost = $_SERVER['REMOTE_ADDR'];
    
    // Insert or update data
    $api->insertOrUpdateData($input, $remoteHost);

    http_response_code(201);  // Success
    echo json_encode(['message' => 'Data inserted or updated successfully.']);
} catch (Exception $e) {
    http_response_code(500);  // Server error
    echo json_encode(['message' => $e->getMessage()]);
}

?>


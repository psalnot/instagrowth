<?php
require_once("../utils/defines.php");

// Database credentials (update with your details)
define('DB_HOST', 'localhost');
define('DB_USER', $DBUSER);
define('DB_PASS', $DBPASS);
define('DB_NAME', $DBNAME);

// Auth key for authorization (replace with your actual auth key)
define('AUTH_KEY', '4242');

// Class to handle Instagram API logic
class InstagramAPI {
    private $conn;

    public function __construct() {
        $this->connectDB();
    }

    // Function to establish the database connection
    private function connectDB() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            $this->sendErrorResponse(500, "Database connection failed");
        }
    }

    // Function to handle the REST API request
    public function handleRequest(?int $param = null): void {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->sendErrorResponse(405, "Only GET method is allowed");
        }

        // Get and validate parameters
        $auth_key = isset($_GET['auth_key']) ? $_GET['auth_key'] : null;
        $username = isset($_GET['username']) ? $_GET['username'] : null;

        if (!$auth_key || !$username) {
            $this->sendErrorResponse(400, "Missing parameters");
        }

        // Check authorization
        if ($auth_key !== AUTH_KEY) {
            $this->sendErrorResponse(401, "Unauthorized: Invalid auth_key");
        }

        if ($param === 42) {
            // Process the username and retrieve data
            $targetData = $this->getTargetData($username);
            if (!$targetData) {
                $this->sendErrorResponse(400, "No data found for username");
            }

            // Retrieve the list of instagram usernames based on retrieved data
            $usernamesList = $this->getInstagramUsernames($username, $targetData['target_name'], $targetData['job_name'], $targetData['id']);

            // Send response with the list
            $this->sendSuccessResponse([
                'instagram_usernames' => $usernamesList,
                'job_name' => $targetData['job_name'],
                'target_name' => $targetData['target_name'],
                    'id' => $targetData['id']
                ]);
        }
        else {
            $usernamesList = $this->getUnprocessedUsernames($username);
            if (!$usernamesList) {
                $this->sendErrorResponse(400, "No unprocessed usernames found for username");
            }

            $this->sendSuccessResponse([
                'instagram_usernames' => $usernamesList,
                'job_name' => "global",
                'target_name' => "global",
                'id' => "42"
            ]);
        }
    }

    // Function to retrieve target data from instagram_target_top_followed table
    private function getTargetData($username) {
        $sql = "SELECT id, target_name, job_name FROM instagram_target_top_followed 
                WHERE target_instagram_interact_username = ? 
                AND is_listed = 0 
                ORDER BY followed DESC LIMIT 1";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);

        // Check if the prepare() failed
        if (!$stmt) {
            // Log the SQL error
            error_log("SQL Error: " . $this->conn->error);
            die("SQL prepare error: " . $this->conn->error); // You can handle the error more gracefully
        }

        // Bind the parameters
        $stmt->bind_param('s', $username);


        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Function to retrieve instagram usernames from instagram_interact table
    private function getInstagramUsernames($instagram_username_interact, $targetName, $jobName, $id) {
        $sql = "SELECT instagram_username FROM instagram_interact 
                WHERE instagram_username_interact = ? 
                AND is_unlist = 0 
                AND target = ? 
                AND followed = 1 
                AND job_name = ?";

        $stmt = $this->conn->prepare($sql);
        #$team = 'cottagedemodddntchamp'; // Assuming this is a static value
        $team = $instagram_username_interact;
        $stmt->bind_param('sss', $team, $targetName, $jobName);
        $stmt->execute();
        $result = $stmt->get_result();

        $usernames = [];
        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row['instagram_username'];
        }

        return $usernames;
    }

    // Function to send a success JSON response
    private function sendSuccessResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Function to send an error JSON response with an error code
    private function sendErrorResponse($errorCode, $errorMessage) {
        http_response_code($errorCode);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => $errorMessage
        ]);
        exit;
    }

    // Close the database connection
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Retrieves unprocessed Instagram usernames for a specific interaction
     * 
     * This function selects instagram_usernames from the instagram_random_followback table
     * where the instagram_username_interact matches the specified username and is_processed is 0.
     *
     * @param string $username The Instagram username to filter interactions for
     * @return array|null Returns an array of unprocessed instagram_usernames or null on error
     */
    public function getUnprocessedUsernames(string $username): ?array {
    try {
            $sql = "SELECT instagram_username 
                    FROM instagram_random_followback 
                    WHERE instagram_username_interact = ? 
                    AND is_processed = 0";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->conn->error);
            }
            
            $stmt->bind_param('s', $username);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $usernames = [];
            
            while ($row = $result->fetch_assoc()) {
                $usernames[] = $row['instagram_username'];
            }
            
            $stmt->close();
            
            return $usernames;
            
        } catch (Exception $e) {
            error_log("Error in getUnprocessedUsernames: " . $e->getMessage());
            return null;
        }
    }

}

$api = new InstagramAPI();


// Create an instance of the API class and handle the request

$api->handleRequest(44);

?>

<?php

require_once("../utils/defines.php");

class InstagramSessionAPI {
    private $pdo;
    private $authKey;

    public function __construct($dbName, $dbUser, $dbPass, $authKey) {
        $this->authKey = $authKey;

        // Create a PDO instance for MySQL connection
        $dsn = "mysql:host=localhost;dbname=$dbName;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $dbUser, $dbPass, $options);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Database connection failed.");
        }
    }

    public function handleRequest() {
        // Check request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["error" => "Only POST requests are allowed."]);
            return;
        }

        // Read and decode the JSON input
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate authkey
        if (!isset($data['authkey']) || $data['authkey'] !== $this->authKey) {
            http_response_code(403);
            echo json_encode(["error" => "Unauthorized access. Invalid authkey."]);
            return;
        }

        // Validate required fields
        if (!$this->validateFields($data)) {
            http_response_code(400);
            echo json_encode(["error" => "Missing or invalid fields in the input data."]);
            return;
        }

        // Process interactions_by_user and successful_by_user
        $this->processUserInteractions($data['interactions_by_user'], $data['successful_by_user'], $data['session_id'], $data['date'], $data['total_interactions'], $data['total_successful']);

        http_response_code(200);
        echo json_encode(["success" => "Data processed successfully."]);
    }

    private function validateFields($data) {
        $requiredFields = ['date', 'session_id', 'total_interactions', 'total_successful', 'interactions_by_user', 'successful_by_user'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || (is_array($data[$field]) && empty($data[$field]))) {
                return false;
            }
        }
        return true;
    }

    private function processUserInteractions($interactionsByUser, $successfulByUser, $sessionId, $date, $totalInteractions, $totalSuccessful) {
        foreach ($interactionsByUser as $username => $interactionCount) {
            $successfulCount = isset($successfulByUser[$username]) ? $successfulByUser[$username] : 0;

            // Check for existing target_name and session_id to avoid duplicates
            if ($this->isDuplicateEntry($username, $sessionId)) {
                // Return specific error message if entry is duplicated
                http_response_code(409); // Conflict
                echo json_encode(["error" => "Duplicate entry for target_name '$username' with session_id '$sessionId'."]);
                return;
            }

            // Insert user data along with session information into the same table
            $this->insertUserData($username, $interactionCount, $successfulCount, $sessionId, $date, $totalInteractions, $totalSuccessful);
        }
    }

    private function insertUserData($username, $totalInteractions, $totalSuccessful, $sessionId, $date, $sessionInteractions, $sessionSuccessful) {
        $query = "INSERT INTO instagram_session_interaction (target_name, total_interactions, total_successful, session_id, date_session, total_session_interactions, total_session_successful)
                  VALUES (:target_name, :total_interactions, :total_successful, :session_id, :date_session, :total_session_interactions, :total_session_successful)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':target_name' => $username,
            ':total_interactions' => $totalInteractions,
            ':total_successful' => $totalSuccessful,
            ':session_id' => $sessionId,
            ':date_session' => $date,
            ':total_session_interactions' => $sessionInteractions,
            ':total_session_successful' => $sessionSuccessful
        ]);
    }

    private function isDuplicateEntry($username, $sessionId) {
        $query = "SELECT COUNT(*) FROM instagram_session_interaction WHERE target_name = :target_name AND session_id = :session_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':target_name' => $username, ':session_id' => $sessionId]);
        return $stmt->fetchColumn() > 0;
    }
}

// Configuration settings
$dbConfig = [
    'dbName' => $DBNAME,
    'dbUser' => $DBUSER,
    'dbPass' => $DBPASS,
    'authKey' => '4242' // The valid auth key
];

// Create the API instance and handle the request
$api = new InstagramSessionAPI($dbConfig['dbName'], $dbConfig['dbUser'], $dbConfig['dbPass'], $dbConfig['authKey']);
$api->handleRequest();
?>


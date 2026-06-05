<?php
/**
 * InstagramInteractAPI Class
 * 
 * This class handles the interaction with the instagram_interact database.
 * It provides a method to update follow-back information based on data received via POST requests.
 */

require_once("../utils/defines.php");


class InstagramInteractAPI {
    private $db;

    /**
     * Constructor to initialize the database connection using PDO.
     */
    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->sendErrorResponse("Database connection failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Main method to process the POST request and update the database.
     */
    public function processRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendErrorResponse("Invalid request method. POST expected.", 405);
        }

        // Fetch the POST data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the received data
        if (!$this->validateData($data)) {
            $this->sendErrorResponse("Invalid input data.", 400);
        }

        // Extract variables from POST data
        $instagram_username = $data['username'];
        $instagram_username_interact = $data['instagram_username_interact'];
        $is_following_back = $data['is_following_back'];
        $is_follow_back_test = $data['is_follow_back_test'];

        // Check if the entry exists in the database
        $id = $this->getEntryId($instagram_username, $instagram_username_interact);

        if ($id !== null) {
            // Update the entry
            $this->updateEntry($id, $is_following_back, $is_follow_back_test);
        } else {
            // Send an error response if no entry is found
            $this->sendErrorResponse("No entry found for username '{$instagram_username}' with interaction '{$instagram_username_interact}'", 404);
        }
    }

    /**
     * Validates the data received from the POST request.
     */
    private function validateData($data) {
        return isset($data['username'], $data['instagram_username_interact'], $data['is_following_back'], $data['is_follow_back_test']) &&
               is_numeric($data['is_following_back']) && is_numeric($data['is_follow_back_test']);
    }

    /**
     * Fetches the entry ID based on the username and interaction.
     */
    private function getEntryId($instagram_username, $instagram_username_interact) {
        try {
            $query = "SELECT id FROM instagram_interact WHERE instagram_username = :instagram_username AND instagram_username_interact = :instagram_username_interact";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':instagram_username' => $instagram_username,
                ':instagram_username_interact' => $instagram_username_interact
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null;
        } catch (PDOException $e) {
            $this->sendErrorResponse("Database query failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Updates an existing entry in the database.
     */
    private function updateEntry($id, $is_following_back, $is_follow_back_test) {
        try {
            $query = "UPDATE instagram_interact SET is_following_back = :is_following_back, is_follow_back_test = :is_follow_back_test WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':is_following_back' => $is_following_back,
                ':is_follow_back_test' => $is_follow_back_test,
                ':id' => $id
            ]);
            $this->sendSuccessResponse("Entry updated successfully.");
        } catch (PDOException $e) {
            $this->sendErrorResponse("Failed to update entry: " . $e->getMessage(), 500);
        }
    }

    /**
     * Sends a JSON success response.
     */
    private function sendSuccessResponse($message) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => $message]);
        exit;
    }

    /**
     * Sends a JSON error response with an appropriate HTTP status code.
     */
    private function sendErrorResponse($message, $statusCode) {
        header('Content-Type: application/json', true, $statusCode);
        echo json_encode(['status' => 'error', 'message' => $message]);
        exit;
    }
}

// Instantiate and run the API
$api = new InstagramInteractAPI("localhost", $DBNAME, $DBUSER, $DBPASS);
$api->processRequest();
?>

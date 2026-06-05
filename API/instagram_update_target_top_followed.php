<?php
// UpdateInstagramTarget.php

require_once("../utils/defines.php");


class InstagramTargetUpdater {
    private $pdo;
    private $authKey;

    public function __construct($dbName, $dbUser, $dbPass, $authKey) {
        $this->authKey = $authKey;

        // Create a PDO instance
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

    public function updateIsListed($authKey, $id) {
        // Check if the provided auth key is valid
        if ($authKey !== $this->authKey) {
            return ["error" => "Unauthorized access."];
        }

        // Validate ID
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ["error" => "Invalid ID."];
        }

        // Prepare and execute the update query
        try {
            $stmt = $this->pdo->prepare("UPDATE instagram_target_top_followed SET is_listed = 1 WHERE id = :id");
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                return ["success" => "Record updated successfully."];
            } else {
                return ["error" => "No record found with the given ID."];
            }
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return ["error" => "An error occurred while updating the record."];
        }
    }
}

// Configuration values

$AUTH_KEY = '4242'; // Your secret auth key

// Create an instance of the class and handle the request
$updater = new InstagramTargetUpdater($DBNAME, $DBUSER, $DBPASS, $AUTH_KEY);

// Get parameters from the request
$authKey = $_GET['auth_key'] ?? null; // Change to POST if required
$id = $_GET['id'] ?? null; // Change to POST if required

// Call the update function and return the response
$response = $updater->updateIsListed($authKey, $id);
echo json_encode($response);
?>

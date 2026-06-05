<?php

require_once("../utils/defines.php");
require_once("../class/instagrowth.class.php");
require_once("../class/mysql.class.php");



class NotificationsEndpoint {
    private string $authKey;
    private ?PDO $db;
    private bool $debug;
    
    public function __construct(string $authKey, PDO $db, bool $debug = false) {
        $this->authKey = $authKey;
        $this->db = $db;
        $this->debug = $debug;
    }
    
    /**
     * Validate the authentication token
     */
    private function validateAuth(): bool {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return false;
        }
        
        return hash_equals($this->authKey, $matches[1]);
    }
    
    /**
     * Validate date format (YYYY-MM-DD)
     */
    private function isValidDate(?string $date): bool {
        if ($date === null || $date === '') {
            return false;
        }
        
        $format = 'Y-m-d';
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }
    
    /**
     * Enhanced data validation including date formats
     */
    private function validateData(array $data): array {
        $required_fields = [
            'instagram_username',
            'instagram_username_interact'
        ];
        
        $errors = [];
        
        // Check required fields
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[] = "Missing required field: {$field}";
            }
        }
        
        // Optional date validation - only if dates are provided
        if (!empty($data['like_date_notification'])) {
            if (!$this->isValidDate($data['like_date_notification'])) {
                $errors[] = "Invalid like_date_notification format. Expected YYYY-MM-DD";
            }
        }
        
        if (!empty($data['followed_date_notification'])) {
            if (!$this->isValidDate($data['followed_date_notification'])) {
                $errors[] = "Invalid followed_date_notification format. Expected YYYY-MM-DD";
            }
        }
        
        return $errors;
    }
    
    /**
     * Debug print helper
     */
    private function debug(string $message, ?array $context = null): void {
        if (!$this->debug) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = $context ? json_encode($context) : '';
        print("[{$timestamp}] {$message} {$contextStr}\n");
    }
    
    /**
     * Check if notification already exists
     */
    private function notificationExists(string $username, string $username_interact, string $target): bool {
        $this->debug('Checking if notification exists', [
            'username' => $username,
            'username_interact' => $username_interact,
            'target' => $target
        ]);

        $sql = "SELECT count(*) as nb FROM instagram_notifications 
                WHERE instagram_username = :username 
                AND instagram_username_interact = :username_interact 
                AND target = :target";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':username_interact' => $username_interact,
            ':target' => $target
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->debug('Notification check result', [
            'exists' => (bool)$result['nb'],
            'count' => $result['nb']
        ]);

        return $result['nb'] > 0;
    }

    /**
     * Store notification data in database
     */
    private function storeNotification(array $data): int {
        $this->debug('Processing notification', ['data' => $data]);
        
        

        try {
            // Check if notification exists with all three required fields
            $exists = $this->notificationExists(
                $data['instagram_username'],
                $data['instagram_username_interact'],
                $data['target'] ?? ''
            );

            // Determine is_checked value based on target field
            $isChecked = isset($data['target']) && $data['target'] !== null && $data['target'] !== '';

            if ($exists) {
                // Update only specific fields as per PRD
                error_log(sprintf(
                    "Instagram Notification API - Updating existing notification for %s and %s",
                    $data['instagram_username'],
                    $data['instagram_username_interact']
                ));
                // Add detailed data logging
                error_log("Instagram Notification API - Updating notification Full Data Array: " . print_r($data, true));
                $sql = "UPDATE instagram_notifications SET
                    target = :target,
                    last_interaction = :last_interaction,
                    job_name = :job_name,
                    is_watched = :is_watched,
                    is_interact_liked = :is_interact_liked,
                    is_interact_followed = :is_interact_followed,
                    is_checked = :is_checked,
                    date_updated = NOW()
                    WHERE instagram_username = :username 
                    AND instagram_username_interact = :username_interact 
                    AND target = :target";

                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':username', $data['instagram_username'], PDO::PARAM_STR);
                $stmt->bindValue(':username_interact', $data['instagram_username_interact'], PDO::PARAM_STR);
                $stmt->bindValue(':target', $data['target'] ?? '', PDO::PARAM_STR);
                $stmt->bindValue(':last_interaction', $data['last_interaction'] ?? null, PDO::PARAM_STR);
                $stmt->bindValue(':job_name', $data['job_name'] ?? null, PDO::PARAM_STR);
                $stmt->bindValue(':is_watched', isset($data['watched']) ? (bool)$data['watched'] : false, PDO::PARAM_BOOL);
                $stmt->bindValue(':is_interact_liked', isset($data['liked']) ? (bool)$data['liked'] : false, PDO::PARAM_BOOL);
                $stmt->bindValue(':is_interact_followed', isset($data['followed']) ? (bool)$data['followed'] : false, PDO::PARAM_BOOL);
                $stmt->bindValue(':is_checked', $isChecked, PDO::PARAM_BOOL);

                $stmt->execute();
                return $stmt->rowCount();
            } else {
                // Insert new record
                $sql = "INSERT INTO instagram_notifications (
                    instagram_username,
                    instagram_username_interact,
                    target,
                    last_interaction,
                    job_name,
                    is_watched,
                    is_interact_liked,
                    is_interact_followed,
                    is_checked,
                    date_created,
                    date_updated
                ) VALUES (
                    :username,
                    :username_interact,
                    :target,
                    :last_interaction,
                    :job_name,
                    :is_watched,
                    :is_interact_liked,
                    :is_interact_followed,
                    :is_checked,
                    NOW(),
                    NOW()
                )";

                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':username', $data['instagram_username'], PDO::PARAM_STR);
                $stmt->bindValue(':username_interact', $data['instagram_username_interact'], PDO::PARAM_STR);
                $stmt->bindValue(':target', $data['target'] ?? '', PDO::PARAM_STR);
                $stmt->bindValue(':last_interaction', $data['last_interaction'] ?? null, PDO::PARAM_STR);
                $stmt->bindValue(':job_name', $data['job_name'] ?? null, PDO::PARAM_STR);
                $stmt->bindValue(':is_watched', isset($data['watched']) ? (bool)$data['watched'] : false, PDO::PARAM_BOOL);
                $stmt->bindValue(':is_interact_liked', isset($data['liked']) ? (bool)$data['liked'] : false, PDO::PARAM_BOOL);
                $stmt->bindValue(':is_interact_followed', isset($data['followed']) ? (bool)$data['followed'] : false, PDO::PARAM_BOOL);
                $stmt->bindValue(':is_checked', $isChecked, PDO::PARAM_BOOL);

                $stmt->execute();
                return (int)$this->db->lastInsertId();
            }
        } catch (PDOException $e) {
            print("Database error: " . $e->getMessage() . "\n");
            throw $e;
        }
    }

    /**
     * Handle the incoming notification request
     */
    public function handleRequest(): void {
        try {
            $this->debug('Received API request', [
                'method' => $_SERVER['REQUEST_METHOD'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);

            // Log request details to nginx error log
            error_log("Instagram Notification API Request - Method: " . $_SERVER['REQUEST_METHOD'] . ", IP: " . $_SERVER['REMOTE_ADDR']);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("Instagram Notification API - Invalid method: " . $_SERVER['REQUEST_METHOD']);
                print("Error: Invalid request method\n");
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                return;
            }
            
            if (!$this->validateAuth()) {
                error_log("Instagram Notification API - Authentication failed");
                print("Error: Authentication failed\n");
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }
            
            $rawData = file_get_contents('php://input');
            #$this->debug('Received data', ['raw' => $rawData]);
            error_log("Instagram Notification API - Raw Data Received: " . $rawData);
            
            $data = json_decode($rawData, true);
            if (!$data) {
                error_log("Instagram Notification API - JSON decode error: " . json_last_error_msg());
                print("Error: Invalid JSON - " . json_last_error_msg() . "\n");
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON']);
                return;
            }
            
            // Log decoded data
            error_log("Instagram Notification API - Decoded Data: " . print_r($data, true));
            
            // Validate data
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                error_log("Instagram Notification API - Validation errors: " . print_r($errors, true));
                $this->debug('Validation failed', [
                    'errors' => $errors,
                    'data' => $data
                ]);
                http_response_code(400);
                echo json_encode(['errors' => $errors]);
                return;
            }
            
            // Store the notification
            $id = $this->storeNotification($data);
            error_log("Instagram Notification API - Successfully stored notification with ID: " . $id);
            
            // Return success response
            http_response_code(201);
            echo json_encode([
                'status' => 'success',
                'message' => 'Notification stored successfully',
                'id' => $id
            ]);
            
        } catch (Exception $e) {
            error_log("Instagram Notification API - Error: " . $e->getMessage());
            print("Server error: " . $e->getMessage() . "\n");
            http_response_code(500);
            echo json_encode([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ]);
        }
    }
}

// Usage
try {
    $db = new PDO(
        'mysql:host='.$DBHOST.';dbname='.$DBNAME.';charset=utf8mb4',
        $DBUSER,
        $DBPASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    #$debug = getenv('DEBUG') === 'true';
    $debug = true;
    $endpoint = new NotificationsEndpoint('4242', $db, $debug);
    $endpoint->handleRequest();
} catch (Exception $e) {
    print("Configuration error: " . $e->getMessage() . "\n");
    http_response_code(500);
    echo json_encode(['error' => 'Server configuration error']);
}
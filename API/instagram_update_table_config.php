<?php

require_once("../utils/defines.php");
require_once("../class/instagrowth.class.php");
require_once("../class/mysql.class.php");

/**
 * Handles updates to Instagram configuration tables.
 * 
 * This class processes requests to update the status of targets
 * in both add and remove configuration tables.
 */
class InstagramUpdateTableConfig {
    private const AUTH_CODE = '4242';
    private mysqli $cstring;
    private MInstagrowth $instagrowth;
    
    /**
     * @var array<string, string> Common error messages
     */
    private const ERROR_MESSAGES = [
        'invalid_auth' => 'Invalid authentication code',
        'invalid_json' => 'Invalid JSON payload',
        'missing_data' => 'Missing required data in payload',
        'invalid_add_target' => 'Invalid add target structure',
        'invalid_remove_target' => 'Invalid remove target structure'
    ];

    /**
     * Required fields for each target type
     */
    private const ADD_TARGET_REQUIRED_FIELDS = [
        'instagram_username',
        'instagram_username_interact',
        'job_name',
        'followers',
        'following',
        'date_created'
    ];

    private const REMOVE_TARGET_REQUIRED_FIELDS = [
        'target',
        'instagram_username_interact',
        'job_name',
        'total_interactions',
        'failure_rate',
        'private_rate',
        'session_id',
        'created_at'
    ];

    /**
     * Constructor initializes database connection and Instagrowth instance
     *
     * @param mysqli $cstring Database connection instance
     */
    public function __construct(mysqli $cstring) {
        $this->cstring = $cstring;
        $this->instagrowth = new MInstagrowth();
    }

    /**
     * Processes incoming API requests
     *
     * @return void Outputs JSON response
     */
    public function processRequest(): void {
        // Set JSON response headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed'
            ]);
            return;
        }

        try {
            // Get JSON payload
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            // Log received data
            error_log("Received payload: " . $json);

            // Validate JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception(self::ERROR_MESSAGES['invalid_json']);
            }

            // Validate auth
            if (!isset($data['auth']) || $data['auth'] !== self::AUTH_CODE) {
                throw new Exception(self::ERROR_MESSAGES['invalid_auth']);
            }

            // Validate targets structure
            if (!isset($data['targets'])) {
                throw new Exception(self::ERROR_MESSAGES['missing_data']);
            }

            // Process updates
            $result = $this->processUpdates($data['targets']);

            // Return success response
            echo json_encode([
                'success' => true,
                'data' => $result,
                'timestamp' => date('Y-m-d H:i:s')
            ]);

        } catch (Exception $e) {
            error_log("Error processing request: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Validates add target structure
     *
     * @param array $target Target data to validate
     * @throws Exception If validation fails
     */
    private function validateAddTarget(array $target): void {
        foreach (self::ADD_TARGET_REQUIRED_FIELDS as $field) {
            if (!isset($target[$field])) {
                throw new Exception(self::ERROR_MESSAGES['invalid_add_target'] . 
                                  ": Missing field '{$field}'");
            }
        }

        // Validate numeric fields
        if (!is_numeric($target['followers']) || !is_numeric($target['following'])) {
            throw new Exception(self::ERROR_MESSAGES['invalid_add_target'] . 
                              ": followers and following must be numeric");
        }
    }

    /**
     * Validates remove target structure
     *
     * @param array $target Target data to validate
     * @throws Exception If validation fails
     */
    private function validateRemoveTarget(array $target): void {
        foreach (self::REMOVE_TARGET_REQUIRED_FIELDS as $field) {
            if (!isset($target[$field])) {
                throw new Exception(self::ERROR_MESSAGES['invalid_remove_target'] . 
                                  ": Missing field '{$field}'");
            }
        }

        // Validate numeric fields
        if (!is_numeric($target['total_interactions']) || 
            !is_numeric($target['failure_rate']) || 
            !is_numeric($target['private_rate'])) {
            throw new Exception(self::ERROR_MESSAGES['invalid_remove_target'] . 
                              ": rate fields must be numeric");
        }
    }

    /**
     * Process updates for both add and remove configurations
     *
     * @param array $targets Target data containing add_targets and remove_targets
     * @return array Update statistics
     * @throws Exception If validation fails
     */
    private function processUpdates(array $targets): array {
        $stats = [
            'add_updates' => 0,
            'remove_updates' => 0
        ];

        // Process remove targets
        if (!empty($targets['remove_targets'])) {
            foreach ($targets['remove_targets'] as $target) {
                // Validate structure
                $this->validateRemoveTarget($target);

                error_log("Processing remove target: " . json_encode($target));
                
                $success = $this->instagrowth->updateRemoveConfigAddTargetList(
                    $this->cstring,
                    $target['instagram_username_interact'],
                    $target['target'],
                    $target['job_name']
                );
                if ($success) $stats['remove_updates']++;
            }
        }

        // Process add targets
        if (!empty($targets['add_targets'])) {
            foreach ($targets['add_targets'] as $target) {
                // Validate structure
                $this->validateAddTarget($target);

                error_log("Processing add target: " . json_encode($target));
                
                $success = $this->instagrowth->updateAddConfigTargetList(
                    $this->cstring,
                    $target['instagram_username_interact'],
                    $target['instagram_username'],
                    $target['job_name']
                );
                if ($success) $stats['add_updates']++;
            }
        }

        return $stats;
    }
}

// Usage
try {
    $msql = new MMsql();
    $cstring = $msql->dbconnect($DBUSER, $DBPASS, $DBNAME);
    
    $handler = new InstagramUpdateTableConfig($cstring);
    $handler->processRequest();

} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server configuration error',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} finally {
    if (isset($cstring)) {
        mysqli_close($cstring);
    }
}

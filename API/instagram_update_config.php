<?php

require_once("../utils/defines.php");
require_once("../class/instagrowth.class.php");
require_once("../class/mysql.class.php");

/**
 * Handles REST API requests for Instagram target configuration updates.
 * 
 * This class processes requests to retrieve lists of Instagram targets
 * for both addition and removal operations.
 */
class InstagramUpdateConfig {
    private const AUTH_CODE = '4242';
    private mysqli $cstring;
    private MInstagrowth $instagrowth;
    
    /**
     * @var array<string, string> Common error messages
     */
    private const ERROR_MESSAGES = [
        'invalid_auth' => 'Invalid authentication code',
        'missing_username' => 'Username parameter is required',
        'invalid_request' => 'Invalid request method'
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
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => self::ERROR_MESSAGES['invalid_request']
            ]);
            return;
        }

        // Validate request data
        $validationResult = $this->validateData();
        if (!$validationResult['success']) {
            http_response_code(400);
            echo json_encode($validationResult);
            return;
        }

        // Process valid request
        $response = $this->buildResponse($_GET['username']);
        echo json_encode($response);
    }

    /**
     * Validates request parameters and authentication
     *
     * @return array{success: bool, error?: string}
     */
    private function validateData(): array {
        // Validate auth code
        if (!isset($_GET['auth']) || $_GET['auth'] !== self::AUTH_CODE) {
            return [
                'success' => false,
                'error' => self::ERROR_MESSAGES['invalid_auth']
            ];
        }

        // Validate username
        if (!isset($_GET['username']) || empty(trim($_GET['username']))) {
            return [
                'success' => false,
                'error' => self::ERROR_MESSAGES['missing_username']
            ];
        }

        return ['success' => true];
    }

    /**
     * Builds response containing both add and remove target lists
     *
     * @param string $username Instagram username
     * @return array Response data structure
     */
    private function buildResponse(string $username): array {
        try {
            $removeTargets = $this->instagrowth->retrieveRemoveTarget($this->cstring, $username);
            $addTargets = $this->instagrowth->retrieveAddTarget($this->cstring, $username);

            return [
                'success' => true,
                'data' => [
                    'remove_targets' => $removeTargets['success'] ? $removeTargets['data'] : [],
                    'add_targets' => $addTargets['success'] ? $addTargets['data'] : []
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            error_log("Error in InstagramUpdateConfig::buildResponse: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Internal server error',
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }
}

// Usage example
try {
    $msql = new MMsql();
    $cstring = $msql->dbconnect($DBUSER, $DBPASS, $DBNAME);
    
    $handler = new InstagramUpdateConfig($cstring);
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

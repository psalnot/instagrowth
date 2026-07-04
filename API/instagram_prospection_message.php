<?php
require_once("../utils/defines.php");
require_once("../class/mysql.class.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function jsonError(int $code, string $message): void {
    http_response_code($code);
    echo json_encode([
        'success'   => false,
        'error'     => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonError(405, 'Invalid request method');
}

if (!isset($_GET['key']) || $_GET['key'] !== '42') {
    jsonError(401, 'Invalid authentication key');
}

if (!isset($_GET['username']) || empty(trim($_GET['username']))) {
    jsonError(400, 'Username parameter is required');
}

$username = trim($_GET['username']);

try {
    $msql    = new MMsql();
    $cstring = $msql->dbconnect($DBUSER, $DBPASS, $DBNAME);

    // Fetch pending usernames inside a transaction to prevent race conditions
    mysqli_begin_transaction($cstring);

    $stmt = $cstring->prepare("
        SELECT instagram_username
        FROM prospection_message
        WHERE target = ?
          AND is_send = 0
        FOR UPDATE
    ");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $usernames = [];
    while ($row = $result->fetch_assoc()) {
        $usernames[] = $row['instagram_username'];
    }
    $stmt->close();

    if (!empty($usernames)) {
        $updateStmt = $cstring->prepare("
            UPDATE prospection_message
            SET is_send = 1
            WHERE target = ?
              AND is_send = 0
        ");
        $updateStmt->bind_param('s', $username);
        $updateStmt->execute();
        $updateStmt->close();
    }

    mysqli_commit($cstring);

    echo json_encode([
        'success'   => true,
        'data'      => $usernames,
        'count'     => count($usernames),
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    if (isset($cstring)) {
        mysqli_rollback($cstring);
    }
    error_log("Error in instagram_prospection_message.php: " . $e->getMessage());
    jsonError(500, 'Internal server error');
} finally {
    if (isset($cstring)) {
        mysqli_close($cstring);
    }
}
?>

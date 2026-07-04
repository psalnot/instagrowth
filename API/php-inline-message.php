<?php
require_once("../utils/defines.php");

if ($argc !== 3) {
    echo "Usage: php php-inline-message.php <username> <days>\n";
    exit(1);
}

$username = $argv[1];
$days = $argv[2];

if (!ctype_digit($days) || (int)$days <= 0) {
    echo "Error: <days> must be a positive integer.\n";
    exit(1);
}
$days = (int)$days;

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=$DBNAME", $DBUSER, $DBPASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Fetch distinct instagram_usernames from notifications within the date window
$query = "
    SELECT DISTINCT instagram_username
    FROM instagram_notifications
    WHERE instagram_username_interact = :username
      AND (is_followed = 1 OR is_liked = 1)
      AND date_created > DATE_SUB(NOW(), INTERVAL :days DAY)
";

$stmt = $pdo->prepare($query);
$stmt->execute([
    'username' => $username,
    'days'     => $days,
]);

$rows = $stmt->fetchAll();

if (empty($rows)) {
    echo "No usernames found for '$username' in the last $days day(s).\n";
    exit(0);
}

echo "Found " . count($rows) . " username(s). Inserting into prospection_message...\n";

$insert = $pdo->prepare("
    INSERT IGNORE INTO prospection_message (target, instagram_username)
    VALUES (:target, :instagram_username)
");

$inserted = 0;
$skipped  = 0;

foreach ($rows as $row) {
    $insert->execute([
        'target'            => $username,
        'instagram_username' => $row['instagram_username'],
    ]);
    if ($insert->rowCount() > 0) {
        $inserted++;
    } else {
        $skipped++;
    }
}

echo "Done. Inserted: $inserted, Skipped (already exist): $skipped.\n";
?>

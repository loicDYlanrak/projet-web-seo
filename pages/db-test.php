<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/function.php';

$status = 'ok';
$message = '';
$dbVersion = '';
$articleCount = 0;
$imageCount = 0;
$userCount = 0;

try {
    $pdo = dbConnection();
    $dbVersion = (string) $pdo->query('SELECT VERSION()')->fetchColumn();
    $articleCount = (int) $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
    $imageCount = (int) $pdo->query('SELECT COUNT(*) FROM images')->fetchColumn();
    $userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $message = 'Connection to MySQL is ready.';
} catch (Throwable $exception) {
    $status = 'error';
    $message = $exception->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database test</title>
</head>
<body>
    <h1>Database status: <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if ($status === 'ok'): ?>
        <p>MySQL version: <?= htmlspecialchars($dbVersion, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Articles in table: <?= $articleCount; ?></p>
        <p>Images in table: <?= $imageCount; ?></p>
        <p>Users in table: <?= $userCount; ?></p>
    <?php endif; ?>
</body>
</html>

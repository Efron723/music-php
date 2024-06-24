<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$actid = isset($_GET['actid']) ? intval($_GET['actid']) : 0;
if ($actid < 1) {
    header('Location: activities-list.php');
    exit;
}

$sql = "DELETE FROM activities WHERE actid=$actid";
$pdo->query($sql);

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'activities-list.php';
if (isset($_SERVER['HTTP_REFERER'])) {
    $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

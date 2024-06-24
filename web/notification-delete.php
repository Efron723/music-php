<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$actid = isset($_GET['notid']) ? intval($_GET['notid']) : 0;
if ($actid < 1) {
  header('Location: notification-list.php');
  exit;
}

$sql = "DELETE FROM notification WHERE notid=$notid";
$pdo->query($sql);

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'notification-list.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

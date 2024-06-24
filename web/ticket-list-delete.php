<?php
require __DIR__. '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
if ($tid < 1) {
  header('Location: ticket-list.php');
  exit;
}

$sql = "DELETE FROM ticket WHERE tid=$tid";
$pdo->query($sql);

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'ticket-list.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");
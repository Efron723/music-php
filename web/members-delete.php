<?php
require __DIR__ . "/admin-required.php";
require __DIR__ . '/../config/pdo-connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 1) {
  header('Location: list.php');
  exit;
}

$sql = "DELETE FROM `members` WHERE id=$id";

$pdo->query($sql);

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'members-list-admin.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

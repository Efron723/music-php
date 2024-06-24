<?php
require __DIR__ . "/admin-required.php";
require __DIR__ . '/../config/pdo-connect.php';

$ids = isset($_GET['ids']) ? $_GET['ids'] : '';
$idsArray = explode(',', $ids); // 將逗號分隔的 ID 字串轉換成陣列

foreach ($idsArray as $id) {
  $id = intval($id);

  if ($id > 0) {
    $sql = "DELETE FROM `employees` WHERE id=$id";
    $pdo->query($sql);
  }
}

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'employees-list-admin.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

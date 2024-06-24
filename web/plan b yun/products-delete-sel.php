<?php
require __DIR__ . "/admin-required.php";
require __DIR__ . '../../config/pdo-connect.php';

$ids = isset($_GET['id']) ? $_GET['id'] : '';
$idsArray = explode(',', $ids); // 將逗號分隔的 ID 字串轉換成陣列

foreach ($idsArray as $id) {
  $actid = intval($id);

  if ($id > 0) {
    $sql = "DELETE FROM `products` WHERE id=$id";
    $pdo->query($sql);
  }
}

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'product-list-admin.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

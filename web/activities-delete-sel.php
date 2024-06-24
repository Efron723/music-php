<?php
require __DIR__ . "/admin-required.php";
require __DIR__ . '/../config/pdo-connect.php';

$ids = isset($_GET['actid']) ? $_GET['actid'] : '';
$idsArray = explode(',', $ids); // 將逗號分隔的 ID 字串轉換成陣列

foreach ($idsArray as $actid) {
  $actid = intval($actid);

  if ($actid > 0) {
    $sql = "DELETE FROM `activities` WHERE actid=$actid";
    $pdo->query($sql);
  }
}

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'activities-list-admin.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

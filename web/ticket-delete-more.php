<?php
require __DIR__ . "/admin-required.php";
require __DIR__ . '/../config/pdo-connect.php';

$tids = isset($_GET['tids']) ? $_GET['tids'] : '';
$tidsArray = explode(',', $tids); // 將逗號分隔的 ID 字串轉換成陣列

foreach ($tidsArray as $tid) {
  $tid = intval($tid);

  if ($tid > 0) {
    $sql = "DELETE FROM `ticket` WHERE tid=$tid";
    $pdo->query($sql);
  }
}

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'ticket-list.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");

<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
  'success' => false, # 有沒有新增成功
  'bodyData' => $_POST,
  'newId' => 0,
];

// TODO: 欄位資料檢查
// 檢查是否有接收到必要的欄位資料
if (!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['sent_time']) || !isset($_POST['noti_class'])) {
  echo json_encode($output);
  exit; // 結束 PHP 程式
}

$sent_time = strtotime($_POST['sent_time']);
if ($sent_time === false) {
  $sent_time = null;
} else {
  $sent_time = date('Y-m-d', $sent_time);
}

$sql = "INSERT INTO `notification`(`title`, `content`, `sent_time`, `noti_class`) VALUES (
    ?,
    ?,
    ?,
    ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['title'],
  $_POST['content'],
  $sent_time,
  $_POST['noti_class'],
]);


$output['success'] = !!$stmt->rowCount(); # 新增了幾筆
$output['newId'] = $pdo->lastInsertId(); # 取得最近的新增資料的primary key

echo json_encode($output);

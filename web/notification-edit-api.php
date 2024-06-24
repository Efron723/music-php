<?php
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
  'success' => false, # 有沒有新增成功
  'bodyData' => $_POST,
];

// TODO: 欄位資料檢查
if (!isset($_POST['notid'])) {
  echo json_encode($output);
  exit; # 結束 php 程式
}

# preg_match(): regexp 比對用 

# mb_strlen(): 算字串的長度

# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL): 檢查 email 格式



$sql = "UPDATE `notification` SET 
`title`=?,
`content`=?,
`sent_time`=?,
`noti_class`=?,
WHERE notid=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['title'],
  $_POST['content'],
  $_POST['sent_time'],
  $_POST['noti_class'],
  $_POST['notid'],
]);


$output['success'] = !!$stmt->rowCount(); # 修改了幾筆


echo json_encode($output);

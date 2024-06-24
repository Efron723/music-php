<?php
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
  'success' => false, # 有沒有新增成功
  'bodyData' => $_POST,
];

// TODO: 欄位資料檢查
if (!isset($_POST['actid'])) {
  echo json_encode($output);
  exit; # 結束 php 程式
}

$a_date = strtotime($_POST['a_date']);
if ($a_date === false) {
  $a_date = null;
} else {
  $a_date = date('Y-m-d', $a_date);
}

// 檢查是否有新的圖片上傳
if (!empty($_FILES['picture']['name'])) {
  // 有新的圖片上傳
  $target_dir = "../img/activities-img/";
  $target_name = $_FILES['picture']['name'];
  $target_file = $target_dir . $target_name;
  move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);

  // 更新檔案路徑到資料庫中
  $sql_update = "UPDATE activities SET picture = ? WHERE actid = ?";
  $stmt_update = $pdo->prepare($sql_update);
  $stmt_update->execute([$target_name, $_POST['actid']]);

  //$output['success'] = !!$stmt_update->rowCount(); # 修改了幾筆
  if ($stmt_update->rowCount() > 0) {
    $output['success'] = true;
  }
} else {
  // 沒有新的圖片上傳，保持原有圖片不變
  // 從數據庫中讀取原始圖片路徑
  $sql_select = "SELECT picture FROM activities WHERE actid = ?";
  $stmt_select = $pdo->prepare($sql_select);
  $stmt_select->execute([$_POST['actid']]);
  $row = $stmt_select->fetch(PDO::FETCH_ASSOC);
  $old_image_path = $row['picture'];

  // 更新檔案路徑到資料庫中（保持原有的路徑）
  $sql_update = "UPDATE activities SET picture = ? WHERE actid = ?";
  $stmt_update = $pdo->prepare($sql_update);
  $stmt_update->execute([$old_image_path, $_POST['actid']]);
}

$sql = "UPDATE `activities` SET 
`activity_class`=?,
`activity_name`=?,
`a_date`=?,
`a_time`=?,
`location`=?,
`address`=?,
`descriptions`=?,
`organizer`=?,
`artist_id`=?
WHERE actid=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['activity_class'],
  $_POST['activity_name'],
  $a_date,
  $_POST['a_time'],
  $_POST['location'],
  $_POST['address'],
  $_POST['descriptions'],
  $_POST['organizer'],
  $_POST['artist_id'],
  $_POST['actid']
]);

if ($stmt->rowCount() > 0) {
  $output['success'] = true;
}
//$output['success'] = !!$stmt->rowCount(); # 修改了幾筆

echo json_encode($output);

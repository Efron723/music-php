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
if (!isset($_POST['activity_name'])) {
    echo json_encode($output);
    exit; // 結束 PHP 程式
}

// if (!isset($_POST['activity_class']) || !isset($_POST['activity_name']) || !isset($_POST['a_date']) || !isset($_POST['a_time']) || !isset($_POST['location']) || !isset($_POST['address']) || !isset($_POST['descriptions']) || !isset($_POST['organizer']) || !isset($_POST['artist_id']) || !isset($_POST['picture'])) {
//     echo json_encode($output);
//     exit; // 結束 PHP 程式
// }

$a_date = strtotime($_POST['a_date']);
if ($a_date === false) {
    $a_date = null;
} else {
    $a_date = date('Y-m-d', $a_date);
}


$sql = "INSERT INTO `activities`(
    `activity_class`,
    `activity_name`,
    `a_date`,
    `a_time`,
    `location`,
    `address`,
    `descriptions`,
    `organizer`,
    `artist_id`,
    `picture`) VALUES (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?)";

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
    $_FILES['picture']['name']
]);

$id = $pdo->lastInsertId(); //最新一筆的ID

# 組裝檔案路徑 並儲存
$target_dir = "../img/activities-img/";
$target_name = $_FILES['picture']['name'];
$target_file = $target_dir . $target_name;
move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);


$output['success'] = !!$stmt->rowCount(); # 新增了幾筆
$output['newId'] = $pdo->lastInsertId(); # 取得最近的新增資料的primary key

echo json_encode($output);
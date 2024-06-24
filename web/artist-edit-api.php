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
if (!isset($_POST['art_name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['ManagementCompany']) || !isset($_POST['phone_number']) || !isset($_POST['debutDate']) || !isset($_POST['art_picture'])) {
    echo json_encode($output);
    exit; // 結束 PHP 程式
}

$de_date = strtotime($_POST['debutDate']);
if ($de_date === false) {
    $de_date = null;
} else {
    $de_date = date('Y-m-d', $de_date);
}


$sql = "INSERT INTO `artist_register`(`art_name`, `email`, `password`, `ManagementCompany`, `phone_number`, `debutDate`, `art_picture`) VALUES (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['art_name'],
    $_POST['email'],
    $_POST['password'],
    $_POST['ManagementCompany'],
    $_POST['phone_number'],
    $de_date,
    $_POST['art_picture'],
]);


$output['success'] = !!$stmt->rowCount(); # 修改了幾筆

echo json_encode($output);
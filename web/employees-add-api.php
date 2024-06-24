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
if (
    !isset($_POST['first_name']) ||
    !isset($_POST['last_name']) ||
    !isset($_POST['email']) ||
    !isset($_POST['passwords']) ||
    !isset($_POST['gender']) ||
    !isset($_POST['phone_number'])
) {
    echo json_encode($output);
    exit; // 結束 PHP 程式
}

// 檢查信箱是否已存在
$email = $_POST['email'];
$sqlCheckEmail = "SELECT * FROM `employees` WHERE `email` = ?";
$stmtCheckEmail = $pdo->prepare($sqlCheckEmail);
$stmtCheckEmail->execute([$email]);
if ($stmtCheckEmail->rowCount() > 0) {
    $output['message'] = '電子信箱已被註冊，請使用其他信箱進行註冊';
    echo json_encode($output);
    exit; // 结束 PHP 程序
}





$sql = "INSERT INTO `employees` (`first_name`, `last_name`, `email`, `passwords`, `gender`, `phone_number`,`created_at`) VALUES (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,NOW() )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['email'],
    $_POST['passwords'],
    $_POST['gender'],
    $_POST['phone_number']
]);


$output['success'] = !!$stmt->rowCount(); # 新增了幾筆
$output['newId'] = $pdo->lastInsertId(); # 取得最近的新增資料的primary key

echo json_encode($output);

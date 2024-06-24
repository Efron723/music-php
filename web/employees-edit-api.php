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
if (!isset($_POST['first_name'])) {
    echo json_encode($output);
    exit; # 結束 php 程式
}

// 生日
// $birthday = strtotime($_POST['birthday']);
// if ($birthday === false) {
//     $birthday = null;
// } else {
//     $birthday = date('Y-m-d', $birthday);
// }


$sql = "UPDATE `employees` SET `first_name`=?, `last_name`=?,`email`=?,`passwords`=?,`gender`=?,`phone_number`=? WHERE id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['email'],
    $_POST['passwords'],
    $_POST['gender'],
    $_POST['phone_number'],
    $_POST['id'],
]);


$output['success'] = !!$stmt->rowCount(); # 修改了幾筆

echo json_encode($output);

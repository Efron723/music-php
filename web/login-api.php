<?php
// require __DIR__ . '/../config/pdo-connect.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
    'success' => false, # 有沒有新增成功
    'bodyData' => $_POST,
];

// TODO: 欄位資料檢查
if (empty($_POST['email']) or empty($_POST['passwords'])) {
    echo json_encode($output);
    exit; # 結束 php 程式
}

# preg_match(): regexp 比對用 
# mb_strlen(): 算字串的長度
# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL): 檢查 email 格式

# 1. 判斷帳號是否正確
$sql = "Select * From employees where email=?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$_POST['email']]);

$row = $stmt->fetch();
if (empty($row)) {
    # 帳號是錯的
    echo json_encode($output);
    exit; # 結束 php 程式
}

// if (password_verify($_POST['passwords'], $row['passwords'])) {
//     $output['success'] = true;

//     # 把登入完成的狀態記錄在 session
//     $_SESSION['admin'] = [
//         'id' => $row['id'],
//         'email' => $row['email'],
//         'nickname' => $row['nickname'],
//     ];
// } else {
//     # 密碼是錯的
//     $output['code'] = 440;
// }




if ($_POST['passwords'] === $row['passwords']) {
    $output['success'] = true;

    # 把登入完成的狀態記錄在 session
    $_SESSION['admin'] = [
        'id' => $row['id'],
        'email' => $row['email'],
        'name' => $row['name'],
    ];
} else {
    # 密碼是錯的
    $output['code'] = 440;
}

echo json_encode($output);

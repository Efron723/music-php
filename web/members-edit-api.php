<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
    'success' => false, # 有沒有新增成功
    'bodyData' => $_POST,
    'newId' => 0,
];

// $output = [
//     'success' => false, // 預設更新失敗
//     'message' => '', // 新增一個 message 欄位用於提供回傳訊息
//     'bodyData' => $_POST,
//     'newId' => 0,
// ];


// TODO: 欄位資料檢查
if (!isset($_POST['name'])) {
    echo json_encode($output);
    exit; # 結束 php 程式
}

// 生日
$birthday = strtotime($_POST['birthday']);
if ($birthday === false) {
    $birthday = null;
} else {
    $birthday = date('Y-m-d', $birthday);
}

// $filename = sha1(uniqid() . rand()) . $ext;
// $uploadPath = __DIR__ . '/../img/members-img/' . $filename;
// $result = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
// if (!$result) {
//     $output['message'] = '圖片上傳失敗';
//     echo json_encode($output);
//     exit;
// }

// 上傳圖片並取得檔案名稱
$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/webp' => '.webp'
];
$ext = $exts[$_FILES['photo']['type']] ?? '';
if (!$ext) {
    $output['message'] = '不支援的圖片格式';
    echo json_encode($output);
    exit;
}


// 上傳圖片處理
$filename = sha1(uniqid() . rand()) . $ext;
$uploadPath = __DIR__ . '/../img/members-img/' . $filename;
$result = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
if (!$result) {
    $output['message'] = '圖片上傳失敗';
    echo json_encode($output);
    exit;
}






$sql = "UPDATE `members` SET `name`=?,`email`=?,`passwords`=?,`gender`=?,`phone_number`=?,`birthday`=?,`address`=?,`photo`=? WHERE id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['name'],
    $_POST['email'],
    $_POST['passwords'],
    $_POST['gender'],
    $_POST['phone_number'],
    $birthday,
    $_POST['address'],
    $filename,
    $_POST['id']
]);
// if ($result) {
//     $output['success'] = true;
//     $output['message'] = '資料更新成功';
// } else {
//     $output['message'] = '資料更新失敗';
// }

$output['success'] = !!$stmt->rowCount(); # 修改了幾筆

echo json_encode($output);

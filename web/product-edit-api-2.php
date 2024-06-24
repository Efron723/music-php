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
if (!isset($_POST['product_name'])) {
    echo json_encode($output);
    exit; # 結束 php 程式
}


// 上傳圖片並取得檔案名稱
$exts = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/webp' => '.webp'
];
$ext = $exts[$_FILES['picture']['type']] ?? '';
if (!$ext) {
    $output['message'] = '不支援的圖片格式';
    echo json_encode($output);
    exit;
}


// 上傳圖片處理
$filename = sha1(uniqid() . rand()) . $ext;
$uploadPath = __DIR__ . '/../img/products-img/' . $filename;
$result = move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath);
if (!$result) {
    $output['message'] = '圖片上傳失敗';
    echo json_encode($output);
    exit;
}






$sql = "UPDATE `products` SET `product_name`=?,`picture`=?,`price`=?,`purchase_quantity`=?,`activitie_id`=? WHERE id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['product_name'],
    $filename,
    $_POST['price'],
    $_POST['purchase_quantity'],
    $_POST['activitie_id'],
    $_POST['id']
]);

$output['success'] = !!$stmt->rowCount(); # 修改了幾筆

echo json_encode($output);

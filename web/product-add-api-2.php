<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
    'success' => false, // 是否新增成功
    'message' => '', // 錯誤或成功訊息
    'newId' => 0, // 新增資料的 ID
];

// 檢查是否有接收到必要的欄位資料
if (
    !isset($_POST['product_name']) ||
    empty($_FILES['picture']) ||
    !isset($_POST['price']) ||
    !isset($_POST['purchase_quantity']) ||
    !isset($_POST['activitie_id'])
) {
    $output['message'] = '缺少必要的欄位資料或上傳的圖片';
    echo json_encode($output);
    exit;
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

$filename = sha1(uniqid() . rand()) . $ext;
$uploadPath = __DIR__ . '/../img/products-img/' . $filename;
$result = move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath);
if (!$result) {
    $output['message'] = '圖片上傳失敗';
    echo json_encode($output);
    exit;
}

// 執行 SQL 插入資料
$sql = "INSERT INTO `products`(`product_name`, `picture`, `price`, `purchase_quantity`, `activitie_id`) VALUES (?, ?, ?, ?, ?";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([
    $_POST['product_name'],
    $filename,
    $_POST['price'],
    $_POST['purchase_quantity'],
    $_POST['activitie_id']
]);

if ($result) {
    $output['success'] = true;
    $output['newId'] = $pdo->lastInsertId();
    $output['message'] = '會員資料新增成功';
} else {
    $output['message'] = '會員資料新增失敗';
}

echo json_encode($output);

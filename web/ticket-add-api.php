<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
    'success' => false, # 有沒有新增成功
    'bodyData' => $_POST,
    'newId' => 0,
];

// 檢查欄位資料是否完整
if (
    !isset($_POST['activities_id']) || empty($_POST['activities_id']) ||
    !isset($_POST['ticket_area']) || empty($_POST['ticket_area']) ||
    !isset($_POST['counts']) || empty($_POST['counts']) ||
    !isset($_POST['price']) || empty($_POST['price'])
) {
    echo json_encode($output);
    exit; // 結束 PHP 程式
}

$sql = "INSERT INTO `ticket`(`activities_id`, `ticket_area`, `counts`, `price`, `created_at`, `editTime`) VALUES (?, ?, ?, ?, NOW(), NOW())";

$stmt = $pdo->prepare($sql);
if (!$stmt) {
    // 如果prepare失敗，返回錯誤訊息
    $output['error'] = $pdo->errorInfo();
    echo json_encode($output);
    exit;
}

$result = $stmt->execute([
    $_POST['activities_id'],
    $_POST['ticket_area'],
    $_POST['counts'],
    $_POST['price'],
]);

if ($result) {
    // 如果execute成功，設置成功標誌和新ID
    $output['success'] = true;
    $output['newId'] = $pdo->lastInsertId();
} else {
    // 如果execute失敗，返回錯誤訊息
    $output['error'] = $stmt->errorInfo();
}

echo json_encode($output);
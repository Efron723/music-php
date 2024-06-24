<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
    'success' => false, // 是否新增成功
    'newId' => 0,
    'error' => '', // 錯誤訊息
];

// 檢查是否有接收到必要的欄位資料
$requiredFields = ['activity_class', 'activity_name', 'a_date', 'a_time', 'location', 'address', 'descriptions', 'organizer', 'artist_id'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        $output['error'] = '缺少必要欄位資料: ' . $field;
        echo json_encode($output);
        exit;
    }
}

// 檢查是否有上傳檔案
if (!isset($_FILES['picture'])) {
    $output['error'] = '未上傳圖片';
    echo json_encode($output);
    exit;
}

// 檢查上傳檔案是否成功
if ($_FILES['picture']['error'] !== UPLOAD_ERR_OK) {
    $output['error'] = '圖片上傳失敗';
    echo json_encode($output);
    exit;
}

// 檢查檔案類型
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['picture']['type'], $allowedTypes)) {
    $output['error'] = '僅支援 JPEG、PNG 和 GIF 格式的圖片';
    echo json_encode($output);
    exit;
}

// 檢查檔案大小
$maxFileSize = 2 * 1024 * 1024; // 2 MB
if ($_FILES['picture']['size'] > $maxFileSize) {
    $output['error'] = '圖片大小不能超過 2MB';
    echo json_encode($output);
    exit;
}

// 移動上傳的檔案到目標目錄
$targetDir = "../img/activities-img/";
$targetName = uniqid() . '_' . $_FILES['picture']['name']; // 使用 uniqid() 避免檔案名稱衝突
$targetFile = $targetDir . $targetName;
if (!move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFile)) {
    $output['error'] = '檔案移動失敗';
    echo json_encode($output);
    exit;
}

// 處理日期 
$a_date = strtotime($_POST['a_date']);
$a_date = $a_date !== false ? date('Y-m-d', $a_date) : null;

// 插入資料到資料庫
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
    `picture`
) VALUES (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?
)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([
    $_POST['activity_class'],
    $_POST['activity_name'],
    $a_date,
    $_POST['a_time'],
    $_POST['location'],
    $_POST['address'],
    $_POST['descriptions'],
    $_POST['organizer'],
    $_POST['artist_id'],
    $targetName // 存儲的是檔案名稱而非檔案路徑
])) {
    $output['success'] = true;
    $output['newId'] = $pdo->lastInsertId();
} else {
    $output['error'] = '資料庫插入失敗';
}

echo json_encode($output);

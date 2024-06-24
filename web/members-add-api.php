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
    !isset($_POST['name']) ||
    !isset($_POST['email']) ||
    !isset($_POST['passwords']) ||
    !isset($_POST['gender']) ||
    !isset($_POST['phone_number']) ||
    !isset($_POST['birthday']) ||
    !isset($_POST['address']) ||
    empty($_FILES['photo'])
) {
    $output['message'] = '缺少必要的欄位資料或上傳的圖片';
    echo json_encode($output);
    exit;
}

// 檢查信箱是否已存在
$email = $_POST['email'];
$sqlCheckEmail = "SELECT * FROM `members` WHERE `email` = ?";
$stmtCheckEmail = $pdo->prepare($sqlCheckEmail);
$stmtCheckEmail->execute([$email]);
if ($stmtCheckEmail->rowCount() > 0) {
    $output['message'] = '電子信箱已被註冊，請使用其他信箱進行註冊';
    echo json_encode($output);
    exit;
}

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

$filename = sha1(uniqid() . rand()) . $ext;
$uploadPath = __DIR__ . '/../img/members-img/' . $filename;
$result = move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
if (!$result) {
    $output['message'] = '圖片上傳失敗';
    echo json_encode($output);
    exit;
}

// 執行 SQL 插入資料
$sql = "INSERT INTO `members`(`name`, `email`, `passwords`, `gender`, `phone_number`, `birthday`, `address`, `photo`, `created_at`) VALUES (?, ?, ?, ?, ?, ?,?,?, NOW())";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([
    $_POST['name'],
    $_POST['email'],
    $_POST['passwords'],
    $_POST['gender'],
    $_POST['phone_number'],
    $_POST['birthday'],
    $_POST['address'],
    $filename
]);

if ($result) {
    $output['success'] = true;
    $output['newId'] = $pdo->lastInsertId();
    $output['message'] = '會員資料新增成功';
} else {
    $output['message'] = '會員資料新增失敗';
}

echo json_encode($output);

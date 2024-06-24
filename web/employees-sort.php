<?php
if (!isset($_SESSION)) {
  session_start();
}

require __DIR__ . './../config/pdo-connect.php';

$per_page = 20; # 每頁有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

// 接收排序欄位名稱
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id';
$sortColumn = in_array($sortColumn, ['id', 'first_name', 'last_name', 'email', 'passwords', 'gender', 'phone_number', 'created_at']) ? $sortColumn : 'id';

$offset = ($page - 1) * $per_page;

// 使用 sprintf 函數動態生成 SQL 查詢
$sql = sprintf(
  "SELECT * FROM `employees` ORDER BY %s DESC LIMIT %s, %s",
  $sortColumn,
  $offset,
  $per_page
);

$rows = $pdo->query($sql)->fetchAll();

echo json_encode([
  'rows' => $rows,
  'sortColumn' => $sortColumn
]);

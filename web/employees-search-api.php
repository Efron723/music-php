<?php
// 假設您有一個資料庫連線 $conn

require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

if (isset($_GET['searchTerm'])) {
  $searchTerm = $_GET['searchTerm'];

  // 準備 SQL 查詢，這裡假設您要在 employees 資料表中搜尋員工姓名
  $sql = "SELECT * FROM employees WHERE 
  first_name LIKE ? OR
  last_name LIKE ? OR
  email LIKE ? OR
  gender LIKE ? OR
  phone_number LIKE ? OR
  created_at LIKE ?";

  $stmt = $conn->prepare($sql);
  $param = "%$searchTerm%";
  $stmt->bind_param("s", $param);
  $stmt->execute();
  $result = $stmt->get_result();

  $rows = [];
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }

  echo json_encode($rows); // 返回 JSON 格式的搜尋結果
} else {
  echo json_encode([]); // 如果沒有傳遞搜索關鍵字，則返回空陣列
}

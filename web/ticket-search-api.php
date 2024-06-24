<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = 'ticket-search-api';
$pageName = 'ticket-search-api';

// 處理 AJAX 請求
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['keyword'])) {
  // 取得前端傳遞過來的關鍵字
  $keyword = $_GET['keyword'];

  // 使用準備語句準備 SQL 查詢
  $sql = "SELECT * FROM ticket 
            JOIN activities ON ticket.activities_id = activities.actid 
            JOIN aclass ON activities.actid = aclass.id 
            JOIN artist ON activities.actid = artist.id 
            WHERE activity_name LIKE :keyword 
            OR descriptions LIKE :keyword";

  $stmt = $pdo->prepare($sql);

  // 將關鍵字加入 SQL 查詢中
  $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);

  // 執行查詢
  $stmt->execute();

  // 取得查詢結果
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 返回 JSON 格式的查詢結果
  header('Content-Type: application/json');
  echo json_encode($results);
  exit; // 結束腳本執行
} else {
  // 如果不是正確的請求方式，返回錯誤訊息
  header("HTTP/1.0 405 Method Not Allowed");
  echo "Method Not Allowed";
  exit;
}
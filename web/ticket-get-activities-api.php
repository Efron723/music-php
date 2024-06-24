<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';


$title = '獲取activities資料';
$pageName = 'ticket-get-activities';

header('Content-Type: application/json');

// 準備 SQL 查詢
$sql = "SELECT 
actid,
activities.picture,
activities.activity_name,
artist.art_name,
activities.location,
activities.descriptions,
activities.a_date,
activities.a_time,
aclass.class,
activities.organizer
FROM 
activities
JOIN 
artist ON activities.artist_id = artist.id
JOIN 
aclass ON activities.activity_class = aclass.id";

// 執行 SQL 查詢並獲取結果集
$stmt = $pdo->query($sql);

// 將結果集轉換為關聯數組
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 將結果轉換為 JSON 格式
$json = json_encode($activities);

// 返回 JSON 數據
echo $json;

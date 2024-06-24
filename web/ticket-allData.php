<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = 'ticket-allData';
$pageName = 'ticket-allData';

// 篩選音樂祭活動
$sql = "SELECT 
*
FROM 
  ticket
JOIN 
  activities ON ticket.activities_id = activities.actid
JOIN 
  aclass ON activities.activity_class = aclass.id
JOIN 
  artist ON activities.artist_id = artist.id;";

$stmt = $pdo->query($sql);
$filteredData = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($filteredData);
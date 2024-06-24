<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = 'get-picture';
$pageName = 'get-picture';

$actid = $_GET['actid']; // 使用 $_GET 来获取参数
$stmt = $pdo->prepare("SELECT picture FROM activities WHERE actid = ?");
$stmt->execute([$actid]);
$picture = $stmt->fetchColumn();

echo $picture;
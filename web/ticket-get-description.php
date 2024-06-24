<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = 'get-description';
$pageName = 'get-description';

$actid = $_GET['actid'];
$stmt = $pdo->prepare("SELECT descriptions FROM activities WHERE actid = ?");
$stmt->execute([$actid]);
$description = $stmt->fetchColumn();

echo $description;
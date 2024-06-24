<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'bodyData' => $_POST,
];

// Check if all required fields are present
if (
    isset($_POST['activities_id']) && !empty($_POST['activities_id']) &&
    isset($_POST['ticket_area']) && !empty($_POST['ticket_area']) &&
    isset($_POST['counts']) && !empty($_POST['counts']) &&
    isset($_POST['price']) && !empty($_POST['price']) &&
    isset($_POST['tid']) && !empty($_POST['tid'])
) {
    $sql = "UPDATE `ticket` SET 
        `activities_id`=?,
        `ticket_area`=?,
        `counts`=?,
        `price`=?,
        `editTime`=NOW()
    WHERE tid=?";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$_POST['activities_id'], $_POST['ticket_area'], $_POST['counts'], $_POST['price'], $_POST['tid']])) {
        $output['success'] = true;
    }
}

echo json_encode($output);
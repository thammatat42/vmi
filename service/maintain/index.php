<?php

header('Content-Type: application/json');
require_once '../connect.php';

$stmt = $connect->prepare("SELECT * FROM tb_master WHERE STATUS_CHG = '0'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($result) > 0) {
    $response = [
        'status' => true,
        'response' => $result,
        'message' => 'Get data success!'
    ];
    http_response_code(200);
    echo json_encode($response);
} else {
    $response = [
        'status' => true,
        'message' => 'error: No data!'
    ];
    http_response_code(401);
    echo json_encode($response);
}
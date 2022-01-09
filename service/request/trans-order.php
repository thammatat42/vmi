<?php

header('Content-Type: application/json');
require_once '../connect.php';

$ORDER_BY = $_SESSION['gid'];

$stmt = $connect->prepare("SELECT ORDER_ID, CART_TRANS, GROUP_ID, IMG, CATEGORY_TH, SIZE, QTY, TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, DATE(ORDER_DATE) AS ORDER_DATE, STATUS_ID, DISTRIBUTE_BY, DATE(DISTRIBUTE_DATE) AS DISTRIBUTE_DATE FROM tb_products WHERE ORDER_BY = :ORDER_BY AND STATUS_CHG = '0'");
$stmt->execute(array("ORDER_BY" => $ORDER_BY));
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
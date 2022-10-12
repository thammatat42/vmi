<?php

header('Content-Type: application/json');
require_once '../connect.php';

$stmt = $connect->prepare("SELECT ORDER_ID, CART_TRANS, GROUP_ID, IMG, CATEGORY_TH, SIZE, QTY, TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, DATE(ORDER_DATE) AS ORDER_DATE, DATE(RECEIVE_DATE) AS RECEIVE_DATE, STATUS_ID, DISTRIBUTE_BY, DATE(DISTRIBUTE_DATE) AS DISTRIBUTE_DATE FROM tb_products WHERE RECEIVE_DATE < DATE(CURDATE()) AND STATUS_ID = 0 AND STATUS_CHG = '0' ORDER BY RECEIVE_DATE ASC");
$stmt->execute(array("ORDER_BY" => $_SESSION['gid']));
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
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'ไม่พบข้อมูล!'));
}

?>
<?php

header('Content-Type: application/json');
require_once '../connect.php';

$stmt = $connect->prepare("SELECT A.*,B.IMG,B.CATEGORY_ENG FROM `tb_stock_master` AS A INNER JOIN tb_master AS B ON A.GROUP_ID = B.GROUP_ID AND A.SIZE = B.SIZE
WHERE A.STATUS_CHG = 0 AND B.STATUS_CHG = 0");
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
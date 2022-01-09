<?php

header('Content-Type: application/json');
require_once '../connect.php';


$stmt = $connect->prepare("SELECT A.GROUP_ID,A.CATEGORY_TH,A.IMG,A.SIZE, CASE WHEN A.C_STOCK < 0 THEN 0 ELSE A.C_STOCK END AS STOCK, B.QTY AS DEMAND,A.C_STOCK AS REMAIN_STOCK,CASE WHEN A.C_STOCK < 0 THEN 'NG' ELSE 'OK' END AS STATUS FROM tb_master as A INNER JOIN
tb_products AS B ON A.GROUP_ID = B.GROUP_ID AND A.SIZE = B.SIZE
WHERE A.STATUS_CHG = 0 AND B.STATUS_ID = 0");
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
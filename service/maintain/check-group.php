<?php
header('Content-Type: application/json');
require_once '../connect.php';

$stmt = $connect->prepare("SELECT DISTINCT GROUP_ID,CATEGORY_TH,CATEGORY_ENG,IMG  FROM tb_group_master WHERE STATUS_CHG = '0'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($result) > 0){
    $response = [
        'status' => true,
        'response' => $result,
        'message' => 'success'
    ];
    http_response_code(200);
    echo json_encode($response);
} else {
    $response = [
        'status' => true,
        'message' => 'error: No data!'
    ];
    http_response_code(404);
    echo json_encode($response);
}

?>
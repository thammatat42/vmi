<?php
header('Content-Type: application/json');
require_once '../connect.php';

$stmt = $connect->prepare("SELECT * FROM tb_order_trans WHERE ORDER_BY = '".$_SESSION['gid']."' AND STATUS_CHG = 0");
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
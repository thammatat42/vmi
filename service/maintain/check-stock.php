<?php
header('Content-Type: application/json');
require_once '../connect.php';

$GROUP_ID = $_POST['id'];

$stmt = $connect->prepare("SELECT SIZE FROM tb_master where GROUP_ID = :id AND STATUS_CHG = 0");
$stmt->execute(array(':id' => $GROUP_ID));
$result_size = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($result_size) > 0){
    http_response_code(200);
    echo json_encode($result_size);
} else {
    $response = [
        'status' => true,
        'message' => 'Error: ไม่พบข้อมูลไซต์ใน Master!'
    ];
    http_response_code(404);
    echo json_encode($response);
}


?>
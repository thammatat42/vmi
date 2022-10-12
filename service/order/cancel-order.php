<?php
header('Content-Type: application/json');
require_once '../connect.php';

// echo "<pre>";
// print_r($_POST);
// die();
$ID = $_POST['id'];

if(isset($_POST['submit'])) {
    
    $sql = "UPDATE tb_order_trans SET STATUS_ID = 3,STATUS_CHG = 1 WHERE ID = :id AND STATUS_CHG = 0";
    $stmt = $connect->prepare($sql);
    $CANCEL_ORDER = $stmt->execute(array(":id" => $ID));

    if($CANCEL_ORDER) {
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'ยกเลิกเรียบร้อยเเล้ว!'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถยกเลิกได้!'));
    }
}

?>
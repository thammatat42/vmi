<?php
header('Content-Type: application/json');
require_once '../connect.php';

// echo "<pre>";
// print_r($_POST);
// die();
$ID = $_POST['id'];
$GROUP_ID = $_POST['index'];
$SIZE = $_POST['size'];
$ORDER_BY = $_SESSION['gid'];

if(isset($_POST['id'])) {
    
    $sql = "UPDATE tb_products SET STATUS_ID = 3 WHERE ORDER_ID = :id AND STATUS_CHG = 0";
    $stmt = $connect->prepare($sql);
    $CANCEL_ORDER = $stmt->execute(array(":id" => $ID));

    $sql_update_trans = "UPDATE tb_order_trans SET STATUS_ID = 0 WHERE GROUP_ID = :GROUP_ID AND SIZE = :SIZE AND ORDER_BY = :ORDER_BY AND STATUS_ID = 1";
    $stmt_update_trans = $connect->prepare($sql_update_trans);
    $CANCEL_ORDER_TRANS = $stmt_update_trans->execute(array(":GROUP_ID" => $GROUP_ID,":SIZE" => $SIZE,":ORDER_BY" => $ORDER_BY));

    if($CANCEL_ORDER && $CANCEL_ORDER_TRANS) {
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'ยกเลิกเรียบร้อยเเล้ว!'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถยกเลิกได้!'));
    }
}

?>
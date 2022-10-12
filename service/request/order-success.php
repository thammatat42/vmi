<?php

header('Content-Type: application/json');
require_once '../connect.php';


if(isset($_POST['uid']) && !empty($_POST['order_id'])) {
    $ORDER_BY = $_POST['uid'];
    $ORDER_ID = $_POST['order_id'];

    $sql = "UPDATE tb_products SET STATUS_ID = 2, DISTRIBUTE_BY = '".$_SESSION['gid']."', DISTRIBUTE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE ORDER_ID IN (".$ORDER_ID.") AND ORDER_BY = :ORDER_BY AND STATUS_ID = 0";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':ORDER_BY', $ORDER_BY);
    $SEND_SUCCESS = $stmt->execute();


    if($SEND_SUCCESS) {
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'ส่งสินค้าเรียบร้อยเเล้ว..'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถส่งสินค้าได้!'));
    }

} else {
    // $ORDER_BY = $_POST['uid'];

    // $sql = "UPDATE tb_products SET STATUS_ID = 2, DISTRIBUTE_BY = '".$_SESSION['gid']."', DISTRIBUTE_DATE = '".date("Y-m-d H:i:s")."'
    // WHERE ORDER_BY = :ORDER_BY AND STATUS_ID = 0";
    // $stmt = $connect->prepare($sql);
    // $SEND_SUCCESS = $stmt->execute(array(":ORDER_BY" => $ORDER_BY));

    // if($SEND_SUCCESS) {
    //     http_response_code(200);
    //     echo json_encode(array('status' => true, 'message' => 'ส่งสินค้าเรียบร้อยเเล้ว..'));
    // } else {
    //     http_response_code(404);
    //     echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถส่งสินค้าได้!'));
    // }
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถทำการส่งสินค้าได้!'));
}




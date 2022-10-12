<?php

header('Content-Type: application/json');
require_once '../connect.php';

// echo '<pre>';
// print_r($_POST);
// die();

$ORDER_ID = $_POST['order_id'];
$CART_TRANS = $_POST['cart_trans'];

if(isset($_POST['order_id'])) {
    $stmt_check = $connect->prepare("SELECT CART_TRANS,GROUP_ID, SIZE, QTY, CATEGORY_TH, ORDER_BY FROM tb_products WHERE ORDER_ID = :ORDER_ID and STATUS_ID = 0");
    $stmt_check->execute(array(":ORDER_ID" => $ORDER_ID));
    $result_check = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    foreach($result_check as $key => $row) {
        $count++;
        $GROUP_ID = $row['GROUP_ID'];
        $SIZE = $row['SIZE'];
        $QTY = $row['QTY'];
        $CART_TRANS = $row['CART_TRANS'];
        $ORDER_BY = $row['ORDER_BY'];

        /* ผลลัพธ์ stock ของ tb_group_master */
        $stmt_group_master = $connect->prepare("SELECT C_STOCK FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
        $stmt_group_master->execute(array(":id" => $GROUP_ID));
        $result_group_master = $stmt_group_master->fetch(PDO::FETCH_OBJ);
        $C_STOCK = $result_group_master->C_STOCK;
        $TOTAL_GROUP_MASTER_STOCK = $C_STOCK + $QTY;
        /* จบ tb_group_master */

        /****************************************************************************************************************************/
        /****************************************************************************************************************************/

        /* ผลลัพธ์ stock ของ tb_master */
        $stmt_master = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
        $stmt_master->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
        $result_master = $stmt_master->fetch(PDO::FETCH_OBJ);
        $MASTER_C_STOCK = $result_master->C_STOCK;
        $TOTAL_MASTER_STOCK = $MASTER_C_STOCK + $QTY;
        /* จบ tb_master */


        $sql2 = "UPDATE tb_master
        SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
        WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
        $stmt_update_stock_master = $connect->prepare($sql2);
        $UPDATE_MASTER = $stmt_update_stock_master->execute(array(":C_STOCK" => $TOTAL_MASTER_STOCK,":id" => $GROUP_ID,":SIZE" => $SIZE));

        $sql3 = "UPDATE tb_group_master
        SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
        WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
        $stmt_update_stock_group_master = $connect->prepare($sql3);
        $UPDATE_GROUP_MASTER = $stmt_update_stock_group_master->execute(array(":C_STOCK" => $TOTAL_GROUP_MASTER_STOCK,":id" => $GROUP_ID));
    
        $sql_order_trans = "UPDATE tb_order_trans SET STATUS_ID = 3 WHERE CART_TRANS = :CART_TRANS AND ORDER_BY = :ORDER_BY AND STATUS_CHG = 1";
        $stmt_order_trans = $connect->prepare($sql_order_trans);
        $stmt_order_trans->bindParam(':CART_TRANS', $CART_TRANS);
        $stmt_order_trans->bindParam(':ORDER_BY', $ORDER_BY);
        $CANCEL_ORDER_TRANS = $stmt_order_trans->execute();
    }

    $sql = "UPDATE tb_products SET STATUS_ID = 3, DISTRIBUTE_BY = '".$_SESSION['gid']."', DISTRIBUTE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE ORDER_ID = :ORDER_ID AND STATUS_ID = 0";
    $stmt = $connect->prepare($sql);
    $SEND_SUCCESS = $stmt->execute(array(":ORDER_ID" => $ORDER_ID));
    

    if($SEND_SUCCESS) {
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'ยกเลิกรายการ: '.$CART_TRANS.' นี้เรียบร้อยเเล้ว..'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถยกเลิกรายการ: '.$CART_TRANS.' นี้ได้!'));
    }

    
}



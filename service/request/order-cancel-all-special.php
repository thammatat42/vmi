<?php

header('Content-Type: application/json');
require_once '../connect.php';

$stmt_check = $connect->prepare("SELECT GROUP_ID, SIZE, QTY, CATEGORY_TH FROM tb_products WHERE DATE(CURDATE()) >= RECEIVE_DATE AND STATUS_ID = 0 AND STATUS_CHG = 0");
$stmt_check->execute();
$result_check = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
foreach($result_check as $key => $row) {
    $count++;
    $GROUP_ID = $row['GROUP_ID'];
    $SIZE = $row['SIZE'];
    $QTY = $row['QTY'];

    /* ผลลัพธ์ stock ของ tb_group_master */
    $stmt_group_master = $connect->prepare("SELECT C_STOCK FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
    $stmt_group_master->bindParam(':id', $GROUP_ID);
    $stmt_group_master->execute();
    $result_group_master = $stmt_group_master->fetch(PDO::FETCH_OBJ);
    $C_STOCK = $result_group_master->C_STOCK;
    $TOTAL_GROUP_MASTER_STOCK = $C_STOCK + $QTY;
    /* จบ tb_group_master */

    /****************************************************************************************************************************/
    /****************************************************************************************************************************/

    /* ผลลัพธ์ stock ของ tb_master */
    $stmt_master = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt_master->bindParam(':id', $GROUP_ID);
    $stmt_master->bindParam(':SIZE', $SIZE);
    $stmt_master->execute();
    $result_master = $stmt_master->fetch(PDO::FETCH_OBJ);
    $MASTER_C_STOCK = $result_master->C_STOCK;
    $TOTAL_MASTER_STOCK = $MASTER_C_STOCK + $QTY;
    /* จบ tb_master */


    $sql2 = "UPDATE tb_master
    SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
    $stmt_update_stock_master = $connect->prepare($sql2);
    $stmt_update_stock_master->bindParam(':id', $GROUP_ID);
    $stmt_update_stock_master->bindParam(':SIZE', $SIZE);
    $stmt_update_stock_master->bindParam(':C_STOCK', $TOTAL_MASTER_STOCK);
    $UPDATE_MASTER = $stmt_update_stock_master->execute();

    $sql3 = "UPDATE tb_group_master
    SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
    $stmt_update_stock_group_master = $connect->prepare($sql3);
    $stmt_update_stock_group_master->bindParam(':id', $GROUP_ID);
    $stmt_update_stock_group_master->bindParam(':C_STOCK', $TOTAL_GROUP_MASTER_STOCK);
    $UPDATE_GROUP_MASTER = $stmt_update_stock_group_master->execute();
}

$sql = "UPDATE tb_products SET STATUS_ID = 3, DISTRIBUTE_BY = '".$_SESSION['gid']."', DISTRIBUTE_DATE = '".date("Y-m-d H:i:s")."'
WHERE DATE(CURDATE()) >= RECEIVE_DATE AND STATUS_ID = 0 AND STATUS_CHG = 0";
$stmt = $connect->prepare($sql);
$SEND_SUCCESS = $stmt->execute();


if($SEND_SUCCESS) {
    http_response_code(200);
    echo json_encode(array('status' => true, 'message' => 'ทำการยกเลิกส่งสินค้าย้อนหลังทั้งหมดเรียบร้อยเเล้ว..'));
} else {
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถยกเลิกรายการย้อนหลังทั้งหมดได้!'));
}


?>
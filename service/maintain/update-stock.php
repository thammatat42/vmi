<?php
header('Content-Type: application/json');
require_once '../connect.php';

if (isset($_POST["submit_group"])) {
    $GROUP_ID = $_POST['check_stock'];
    $SIZE = $_POST['size'];
    $CATEGORY_TH = $_POST['value_id'];
    $INPUT_NUMBER = $_POST['input_number'];
    $adjust_inventory = 0;

    /* เช็ค max stock */
    $stmt_check_max = $connect->prepare("SELECT GROUP_ID,CATEGORY_TH,CATEGORY_ENG,IMG,C_STOCK,TOTAL_MAX,TOTAL_MIN,CREATE_BY,CREATE_DATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG FROM (
        SELECT A.*,SUM(B.MAX_STOCK) AS TOTAL_MAX, SUM(B.MIN_STOCK) AS TOTAL_MIN FROM tb_master AS A INNER JOIN tb_stock_master AS B ON A.GROUP_ID = B.GROUP_ID  AND A.SIZE = B.SIZE
        WHERE B.GROUP_ID = :GROUP_ID AND B.SIZE = :SIZE AND A.STATUS_CHG = '0' AND B.STATUS_CHG = 0 GROUP BY B.GROUP_ID
    ) GROUP_MASTER");
    $stmt_check_max->bindParam(':GROUP_ID', $GROUP_ID);
    $stmt_check_max->bindParam(':SIZE', $SIZE);
    $stmt_check_max->execute();
    $result_max = $stmt_check_max->fetch(PDO::FETCH_OBJ);
    $MAX_STOCK = $result_max->TOTAL_MAX;


    /* ผลลัพธ์ stock ของ tb_group_master */
    $stmt = $connect->prepare("SELECT C_STOCK FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
    $stmt->bindParam(':id', $GROUP_ID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $C_STOCK = $result->C_STOCK;
    $TOTAL_GROUP_MASTER_STOCK = $C_STOCK + $INPUT_NUMBER;
    /* จบ tb_group_master */

    /****************************************************************************************************************************/
    /****************************************************************************************************************************/

    /* ผลลัพธ์ stock ของ tb_master */
    $stmt2 = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt2->bindParam(':id', $GROUP_ID);
    $stmt2->bindParam(':SIZE', $SIZE);
    $stmt2->execute();
    $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
    $MASTER_C_STOCK = $result2->C_STOCK;
    $TOTAL_MASTER_STOCK = $MASTER_C_STOCK + $INPUT_NUMBER;
    /* จบ tb_master */

    if($TOTAL_GROUP_MASTER_STOCK <= $MAX_STOCK && $TOTAL_MASTER_STOCK <= $MAX_STOCK) {
        /* ต้อง update ข้อมูลทั้ง group_master เเละ master 
        Noted: group_master = จำนวนสินค้าทั้งหมด, master = จำนวนสินค้าเเบ่งจำเเนกจาก size */
        $sql = "INSERT INTO tb_update_stock_history (GROUP_ID,CATEGORY_TH,SIZE,STOCK_UPDATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG)
            VALUES (:id, :CATEGORY_TH, :SIZE, :STOCK_UPDATE, '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','0')";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':id', $GROUP_ID);
        $stmt->bindParam(':CATEGORY_TH', $CATEGORY_TH);
        $stmt->bindParam(':SIZE', $SIZE);
        $stmt->bindParam(':STOCK_UPDATE', $INPUT_NUMBER);
        $INSERT_STOCK_HISTORY = $stmt->execute();
        


        $sql2 = "UPDATE tb_master
        SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
        WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
        $stmt2 = $connect->prepare($sql2);
        $stmt2->bindParam(':id', $GROUP_ID);
        $stmt2->bindParam(':C_STOCK', $TOTAL_MASTER_STOCK);
        $stmt2->bindParam(':SIZE', $SIZE);
        $UPDATE_MASTER = $stmt2->execute();

        $sql3 = "UPDATE tb_group_master
        SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
        WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
        $stmt3 = $connect->prepare($sql3);
        $stmt3->bindParam(':id', $GROUP_ID);
        $stmt3->bindParam(':C_STOCK', $TOTAL_GROUP_MASTER_STOCK);
        $UPDATE_GROUP_MASTER = $stmt3->execute();

    } else {
        //รหัส code error inventory 101 = คลังสินค้าไม่พอ
        $adjust_inventory = 101;
    }

    if($adjust_inventory == 101) { 
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ (101): ไม่สามารถทำการอัพเดทสต็อกได้ เนื่องจากจำนวนที่ adjust เข้ามามีมากกว่า Max stock!'));
    } else {
        if($UPDATE_MASTER && $UPDATE_GROUP_MASTER && $INSERT_STOCK_HISTORY){
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'อัพเดทสต๊อกเรียบร้อยเเล้ว..'));
        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถทำการอัพเดทสต็อกได้!'));
        }
    }

}

?>
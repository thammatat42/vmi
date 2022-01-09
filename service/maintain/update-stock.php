<?php
header('Content-Type: application/json');
require_once '../connect.php';

$GROUP_ID = $_POST['check_stock'];
$SIZE = $_POST['size'];
$CATEGORY_TH = $_POST['value_id'];
$INPUT_NUMBER = $_POST['input_number'];


/* ผลลัพธ์ stock ของ tb_group_master */
$stmt = $connect->prepare("SELECT C_STOCK FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
$stmt->execute(array(":id" => $GROUP_ID));
$result = $stmt->fetch(PDO::FETCH_OBJ);
$C_STOCK = $result->C_STOCK;
$TOTAL_GROUP_MASTER_STOCK = $C_STOCK + $INPUT_NUMBER;
/* จบ tb_group_master */

/****************************************************************************************************************************/
/****************************************************************************************************************************/

/* ผลลัพธ์ stock ของ tb_master */
$stmt2 = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
$stmt2->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
$result2 = $stmt2->fetch(PDO::FETCH_OBJ);
$MASTER_C_STOCK = $result2->C_STOCK;
$TOTAL_MASTER_STOCK = $MASTER_C_STOCK + $INPUT_NUMBER;
/* จบ tb_master */


if (isset($_POST["submit_group"])) {

    /* ต้อง update ข้อมูลทั้ง group_master เเละ master 
    Noted: group_master = จำนวนสินค้าทั้งหมด, master = จำนวนสินค้าเเบ่งจำเเนกจาก size
    */
    $sql = "INSERT INTO tb_update_stock_history (GROUP_ID,CATEGORY_TH,SIZE,STOCK_UPDATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG)
            VALUES (:id, :CATEGORY_TH, :SIZE, :STOCK_UPDATE, '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','0')";
    $stmt = $connect->prepare($sql);
    $INSERT_STOCK_HISTORY = $stmt->execute(array(":id" => $GROUP_ID,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE, ":STOCK_UPDATE" => $TOTAL_MASTER_STOCK));

        
    $sql2 = "UPDATE tb_master
    SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
    $stmt2 = $connect->prepare($sql2);
    $UPDATE_MASTER = $stmt2->execute(array(":C_STOCK" => $TOTAL_MASTER_STOCK,":id" => $GROUP_ID,":SIZE" => $SIZE));

    $sql3 = "UPDATE tb_group_master
    SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
    $stmt3 = $connect->prepare($sql3);
    $UPDATE_GROUP_MASTER = $stmt3->execute(array(":C_STOCK" => $TOTAL_GROUP_MASTER_STOCK,":id" => $GROUP_ID));

    if($UPDATE_MASTER && $UPDATE_GROUP_MASTER && $INSERT_STOCK_HISTORY){
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'Updating completed.'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..!'));
    }
    
}

?>
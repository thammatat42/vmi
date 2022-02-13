<?php
header('Content-Type: application/json');
require_once '../connect.php';

// echo '<pre>';
// print_r($_POST);
// die();

$ID = $_POST['id'];
$GROUP_ID = $_POST['index'];
$SIZE = $_POST['size'];

if(isset($_POST['id'])) {
    $stmt = $connect->prepare("SELECT COUNT(*) AS COUNT_CHECK_TRANS FROM tb_products WHERE GROUP_ID = :id AND SIZE = :size AND STATUS_ID = 0");
    $stmt->execute(array(":id" => $GROUP_ID,":size" => $SIZE));
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $COUNT_CHECK_TRANS = $result->COUNT_CHECK_TRANS;

    $stmt_count_stock = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt_count_stock->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
    $result_count_stock = $stmt_count_stock->fetch(PDO::FETCH_OBJ);
    $COUNT_STOCK = $result_count_stock->C_STOCK;
    

    if($COUNT_CHECK_TRANS > 0) {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'ไม่สามารถลบข้อมูล master group: '.$GROUP_ID.' นี้ได้เนื่องจากมีรายการออเดอร์ใช้งานอยู่!'));
    } elseif($COUNT_STOCK > 0) {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'ไม่สามารถลบข้อมูล master group: '.$GROUP_ID.' นี้ได้เนื่องจากมีสินค้าเหลืออยู่!'));
    } else {
        $sql = "UPDATE tb_master SET UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."', STATUS_CHG = '1'
        WHERE ITEM = :ITEM
        ";
        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':ITEM', $ID);
        $DELETE_MASTER = $stmt->execute();

        $sql_delete_stock_master = "UPDATE tb_stock_master SET UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."', STATUS_CHG = 1
        WHERE GROUP_ID = :GROUP_ID AND SIZE = :SIZE AND STATUS_CHG = 0
        ";
        $stmt_stock_master = $connect->prepare($sql_delete_stock_master);
        $stmt_stock_master->bindParam(':GROUP_ID', $GROUP_ID);
        $stmt_stock_master->bindParam(':SIZE', $SIZE);
        $DELETE_STOCK_MASTER = $stmt_stock_master->execute();

        if($DELETE_MASTER && $DELETE_STOCK_MASTER) {
            $response = [
                'status' => true,
                'message' => 'Delete Success'
            ];
            http_response_code(200);
            echo json_encode($response);
        
        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'ไม่สามารถลบข้อได้..คิวรี่ผิดปกติ!'));
        }
    }
}

?>
<?php
header('Content-Type: application/json');
require_once '../connect.php';


if(isset($_POST['submit'])) {
    $ID = $_POST['ID'];
    $MAX_STOCK = $_POST['max_stock'];
    $MIN_STOCK = $_POST['min_stock'];


    $sql = "UPDATE tb_stock_master
    SET MAX_STOCK = :MAX_STOCK, MIN_STOCK = :MIN_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE ID = :ID AND STATUS_CHG = '0'";
    $stmt= $connect->prepare($sql);
    $stmt->bindParam(':MAX_STOCK', $MAX_STOCK);
    $stmt->bindParam(':MIN_STOCK', $MIN_STOCK);
    $stmt->bindParam(':ID', $ID);
    $UPDATE_STOCK_MASTER = $stmt->execute();

    if($UPDATE_STOCK_MASTER){
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Updating completed.'));
    } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเเก้ไขข้อมูลได้!'));
    }
}

?>
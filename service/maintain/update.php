<?php
header('Content-Type: application/json');
require_once '../connect.php';

// echo '<pre>';
// print_r($_POST);
// die();

$type_emp = $_POST['type_emp'];

/* Fix code เนื่องจาก paremeters ที่ส่งกลับมารอบหลังที่จะ submit ปล่าวๆเป็น string */
if($type_emp == 'STT') {
    $type_emp = 2;
} elseif($type_emp == 'STUDENT') {
    $type_emp = 3;
} elseif($type_emp == 'SUBCON'){
    $type_emp = 1;
} elseif($type_emp == 'All'){
    $type_emp = 0;
}

if($type_emp == '0') {
    $type_emp = 'All';
} elseif($type_emp == 'Subcon,Student') {
    $type_emp = 'Subcon,Student';
} else {
    // print_r($type_emp);
    $stmt = $connect->prepare("SELECT DISTINCT EMP_TYPE FROM tb_emp_group_master WHERE GROUP_ID IN (:type_emp)");
    $stmt->bindParam(':type_emp', $type_emp);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    $type_emp = $result->EMP_TYPE;

}
$group_id = $_POST['group_id'];
$Item = $_POST['Item'];
$category_eng = $_POST['category_eng'];
$category_th = $_POST['category_th'];
$size = $_POST['size'];
$price = $_POST['price'];
$level_emp = $_POST['level_emp'];
$department = $_POST['department'];
$namefiles = $_POST['namefiles'];
$MAX_STOCK = $_POST['max_stock'];
$MIN_STOCK = $_POST['min_stock'];


if (isset($_POST["submit"])) {

    $stmt_check_size_old = $connect->prepare("SELECT SIZE FROM tb_master WHERE ITEM = :ITEM AND STATUS_CHG = '0'");
    $stmt_check_size_old->bindParam(':ITEM', $Item);
    $stmt_check_size_old->execute();
    $result_check_size_old = $stmt_check_size_old->fetch(PDO::FETCH_OBJ);

    if($result_check_size_old) {
        $SIZE_OLD = $result_check_size_old->SIZE;

        $sql = "UPDATE tb_master
        SET CATEGORY_ENG = :CATEGORY_ENG, CATEGORY_TH = :CATEGORY_TH, IMG = '".$namefiles."', SIZE = :SIZE, UNIT_PRICE = :UNIT_PRICE, EMP_TYPE = :EMP_TYPE, EMP_LEVEL = :EMP_LEVEL, DEPARTMENT = :DEPARTMENT, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
        WHERE ITEM = :ITEM AND STATUS_CHG = '0'";
        $stmt= $connect->prepare($sql);
        $stmt->bindParam(':CATEGORY_ENG', $category_eng);
        $stmt->bindParam(':CATEGORY_TH', $category_th);
        $stmt->bindParam(':SIZE', $size);
        $stmt->bindParam(':UNIT_PRICE', $price);
        $stmt->bindParam(':EMP_TYPE', $type_emp);
        $stmt->bindParam(':EMP_LEVEL', $level_emp);
        $stmt->bindParam(':DEPARTMENT', $department);
        $stmt->bindParam(':ITEM', $Item);
        $UPDATE_MASTER = $stmt->execute();

        

        $sql_stock_master = "UPDATE tb_stock_master
        SET SIZE = :SIZE, MAX_STOCK = :MAX_STOCK, MIN_STOCK = :MIN_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
        WHERE GROUP_ID = :GROUP_ID AND SIZE = :SIZE_OLD AND STATUS_CHG = 0";
        $stmt_stock_master = $connect->prepare($sql_stock_master);
        $stmt_stock_master->bindParam(':SIZE', $size);
        $stmt_stock_master->bindParam(':MAX_STOCK', $MAX_STOCK);
        $stmt_stock_master->bindParam(':MIN_STOCK', $MIN_STOCK);
        $stmt_stock_master->bindParam(':SIZE_OLD', $SIZE_OLD);
        $stmt_stock_master->bindParam(':GROUP_ID', $group_id);
        $UPDATE_STOCK_MASTER = $stmt_stock_master->execute();

        if($UPDATE_MASTER && $UPDATE_STOCK_MASTER) {
                http_response_code(200);
                echo json_encode(array('status' => true, 'message' => 'Updating completed.'));
        } else {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเเก้ไขข้อมูลได้!'));
        }

    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่พบไซต์ที่จะทำการเเก้ไข!'));
    }
    
}

?>
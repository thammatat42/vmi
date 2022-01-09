<?php
header('Content-Type: application/json');
require_once '../connect.php';


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
} else {
    // print_r($type_emp);
    $stmt = $connect->prepare("SELECT DISTINCT EMP_TYPE FROM tb_emp_group_master WHERE GROUP_ID = :type_emp");
    $stmt->execute(array(":type_emp" => $type_emp));
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


if (isset($_POST["submit"])) {
   
    $sql = "UPDATE tb_master
    SET CATEGORY_ENG = :CATEGORY_ENG, CATEGORY_TH = :CATEGORY_TH, IMG = '".$namefiles."', SIZE = :SIZE, UNIT_PRICE = :UNIT_PRICE, EMP_TYPE = :EMP_TYPE, EMP_LEVEL = :EMP_LEVEL, DEPARTMENT = :DEPARTMENT, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
    WHERE ITEM = :ITEM AND STATUS_CHG = '0'";
    $stmt= $connect->prepare($sql);

    if($stmt->execute(array(":CATEGORY_ENG" => $category_eng,":CATEGORY_TH" => $category_th, ":SIZE" => $size,
    ":UNIT_PRICE" => $price,":EMP_TYPE" => $type_emp,":EMP_LEVEL" => $level_emp,":DEPARTMENT" => $department,":ITEM" => $Item))){
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'Updating completed.'));
    } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเเก้ไขข้อมูลได้!'));
    }
    
}

?>
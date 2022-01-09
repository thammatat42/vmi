<?php
header('Content-Type: application/json');
require_once '../connect.php';

// print_r($_POST);
// die();


$type_emp = $_POST['type_emp'];
$group_id = $_POST['group_id'];
$category_eng = $_POST['category_eng'];
$category_th = $_POST['category_th'];
$size = $_POST['size'];
$price = $_POST['price'];
$level_emp = $_POST['level_emp'];
$department = $_POST['department'];
$namefiles = $_POST['namefiles'];


if (isset($_POST["submit"])) {
    if($type_emp == '0') {
        $type_emp = 'All';
        // print_r($type_emp);
    } else {
        // print_r($type_emp);
        $stmt = $connect->prepare("SELECT DISTINCT EMP_TYPE FROM tb_emp_group_master WHERE GROUP_ID = :type_emp");
        $stmt->execute(array(":type_emp" => $type_emp));
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if($result <> ''){
    
            $type_emp = $result->EMP_TYPE;
    
        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเพิ่มข้อมูลได้!'));
    
            exit();
        }
    
    }

    $stmt = $connect->prepare("SELECT count(*) as chk_count FROM tb_master WHERE GROUP_ID = :GROUP_ID AND SIZE = :SIZE AND EMP_TYPE = :EMP_TYPE AND EMP_LEVEL = :EMP_LEVEL AND DEPARTMENT = :DEPARTMENT AND STATUS_CHG = '0'");
    $stmt->execute(array(":GROUP_ID" => $group_id,":SIZE" => $size,":EMP_TYPE" => $type_emp,":EMP_LEVEL" => $level_emp,":DEPARTMENT" => $department));
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $count = $result->chk_count;

    if($count > 0){
            
        http_response_code(403);
        echo json_encode(array('status' => false, 'message' => 'มีข้อมูลอยู่เเล้ว..ไม่สามารถเพิ่มได้!'));
    
    } else {
        // empty($size) || empty($price) ||
 

        if(empty($size) || empty($price) || $level_emp == 'null' || $type_emp == 'null' || $department == 'null') {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เนื่องจากข้อมูลว่าง..ไม่สามารถเพิ่มข้อมูลได้!'));
        } else {
            
            $sql = "INSERT INTO tb_master (GROUP_ID,CATEGORY_ENG,CATEGORY_TH,IMG,SIZE,UNIT_PRICE,EMP_TYPE,EMP_LEVEL,DEPARTMENT,CREATE_BY,CREATE_DATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG)
            VALUES (:GROUP_ID, :CATEGORY_ENG, :CATEGORY_TH, '".$namefiles."', :SIZE, :UNIT_PRICE, :EMP_TYPE, :EMP_LEVEL, :DEPARTMENT, '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."', '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','0')";
            $stmt= $connect->prepare($sql);

            if($stmt->execute(array(":GROUP_ID" => $group_id, ":CATEGORY_ENG" => $category_eng, ":CATEGORY_TH" => $category_th, ":SIZE" => $size, 
            ":UNIT_PRICE" => $price, ":EMP_TYPE" => $type_emp, ":EMP_LEVEL" => $level_emp, ":DEPARTMENT" => $department))){
                http_response_code(200);
                echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ.'));
            } else {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเพิ่มข้อมูลได้!'));
            }
        }
        
            
    }
}

?>
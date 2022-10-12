<?php
header('Content-Type: application/json');
require_once '../connect.php';

// echo '<pre>';
// print_r($_POST);
// die();



if (isset($_POST["submit"])) {

    $type_emp = $_POST['type_emp'];
    $group_id = $_POST['group_id'];
    $category_eng = $_POST['category_eng'];
    $category_th = $_POST['category_th'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $level_emp = $_POST['level_emp'];
    $department = $_POST['department'];
    $namefiles = $_POST['namefiles'];
    $max_stock = $_POST['max_stock'];
    $min_stock = $_POST['min_stock'];

    if($type_emp == '0') {
        $type_emp = 'All';
        // print_r($type_emp);
    } elseif($type_emp == 'Subcon,Student') {
        $type_emp = 'Subcon,Student';
    } else {
        // print_r($type_emp);
        $stmt = $connect->prepare("SELECT DISTINCT EMP_TYPE FROM tb_emp_group_master WHERE GROUP_ID = :type_emp");
        $stmt->bindParam(':type_emp', $type_emp);
        $stmt->execute();

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
    $stmt->bindParam(':GROUP_ID', $group_id);
    $stmt->bindParam(':SIZE', $size);
    $stmt->bindParam(':EMP_TYPE', $type_emp);
    $stmt->bindParam(':EMP_LEVEL', $level_emp);
    $stmt->bindParam(':DEPARTMENT', $department);
    $stmt->execute();
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
            $stmt->bindParam(':GROUP_ID', $group_id);
            $stmt->bindParam(':CATEGORY_ENG', $category_eng);
            $stmt->bindParam(':CATEGORY_TH', $category_th);
            $stmt->bindParam(':SIZE', $size);
            $stmt->bindParam(':UNIT_PRICE', $price);
            $stmt->bindParam(':EMP_TYPE', $type_emp);
            $stmt->bindParam(':EMP_LEVEL', $level_emp);
            $stmt->bindParam(':DEPARTMENT', $department);
            $INSERT_MASTER = $stmt->execute();

            $sql_stock_master = "INSERT INTO tb_stock_master (GROUP_ID,CATEGORY_TH,SIZE,MAX_STOCK,MIN_STOCK,CREATE_BY,CREATE_DATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG)
            VALUES (:GROUP_ID, :CATEGORY_TH, :SIZE, :MAX_STOCK, :MIN_STOCK, '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."', '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."',0)";
            $stmt_stock_master = $connect->prepare($sql_stock_master);
            $stmt_stock_master->bindParam(':GROUP_ID', $group_id);
            $stmt_stock_master->bindParam(':CATEGORY_TH', $category_th);
            $stmt_stock_master->bindParam(':SIZE', $size);
            $stmt_stock_master->bindParam(':MAX_STOCK', $max_stock);
            $stmt_stock_master->bindParam(':MIN_STOCK', $min_stock);
            $INSERT_STOCK_MASTER = $stmt_stock_master->execute();
            

            if($INSERT_MASTER && $INSERT_STOCK_MASTER){
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
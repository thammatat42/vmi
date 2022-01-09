<?php
header('Content-Type: application/json');
require_once '../connect.php';

$ID = $_POST['value_id'];
$CATE_ENG = $_POST['group_category_ENG_edit'];
$CATE_TH = $_POST['group_category_TH_edit'];

$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$datetime = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );


// Check if image file is a actual image or fake image
if (isset($_POST["submit_edit"])) {

    if($_FILES){
        $CHG_FILES = explode(".",$_FILES["customfile"]["name"]);
        $CHG_FILES = $datetime->format("Ymdu").'.'.$CHG_FILES[1];
        $target_dir = "../../assets/images/uniforms/";
        $target_file = $target_dir . basename($CHG_FILES);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        

        // Check if file already existselse 
        if (file_exists($target_file)) {
            $msg = "ไฟล์นี้มีอยู่เเล้ว";
            $_SESSION['message'] = $msg;
            $uploadOk = 0;
            http_response_code(403);
            echo json_encode(array('status' => false, 'message' => 'This file already exists.'));
        } 
        // Check file sizeelse 
        if ($_FILES["customfile"]["size"] > 5000000) {
            $msg = "ไฟล์ของคุณใหญ่เกินต้องไม่เกิน 5MB!";
            $_SESSION['message'] = $msg;
            $uploadOk = 0;
            http_response_code(403);
            echo json_encode(array('status' => false, 'message' => 'This file to large!! <= 5MB.'));
        } 
        // Check if $uploadOk is set to 0 by an errorelse 
        if ($uploadOk == 0) {
            $msg = "Sorry, your file was not uploaded.";
    
        /* if everything is ok, try to upload file */
        } else {
    
            if (move_uploaded_file($_FILES["customfile"]["tmp_name"], $target_file)) {
                $sql = "UPDATE tb_group_master SET CATEGORY_ENG = :group_category_ENG_edit, CATEGORY_TH = :group_category_TH_edit, IMG = '".$CHG_FILES."', UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                WHERE GROUP_ID = :value_id AND STATUS_CHG = '0'";
                $stmt= $connect->prepare($sql);
    
                if($stmt->execute(array(":value_id" => $ID,":group_category_ENG_edit" => $CATE_ENG,":group_category_TH_edit" => $CATE_TH))){
                    $sql = "UPDATE tb_master SET CATEGORY_ENG = :group_category_ENG_edit, CATEGORY_TH = :group_category_TH_edit, IMG = '".$CHG_FILES."', UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                    WHERE GROUP_ID = :value_id AND STATUS_CHG = '0'";
                    $stmt= $connect->prepare($sql);
                    $stmt->execute(array(":value_id" => $ID,":group_category_ENG_edit" => $CATE_ENG,":group_category_TH_edit" => $CATE_TH));

                    http_response_code(200);
                    echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ'));
                } else {
                    http_response_code(404);
                    echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเพิ่มข้อมูลได้!'));
                }
            }  
        }
    } else {
        $sql = "UPDATE tb_group_master SET CATEGORY_ENG = :group_category_ENG_edit, CATEGORY_TH = :group_category_TH_edit, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                WHERE GROUP_ID = :value_id AND STATUS_CHG = '0'";
        $stmt= $connect->prepare($sql);

        if($stmt->execute(array(":value_id" => $ID,":group_category_ENG_edit" => $CATE_ENG,":group_category_TH_edit" => $CATE_TH))){
                http_response_code(200);
                echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ'));
        } else {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเพิ่มข้อมูลได้!'));
        }
    }

    
}


?>
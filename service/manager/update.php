<?php
header('Content-Type: application/json');
require_once '../connect.php';


// print_r($_POST);
// print_r($_FILES);
// die();
$UID = $_POST['username'];
$FNAME = $_POST['first_name'];
$LNAME = $_POST['last_name'];
$STATUS_TYPE = $_POST['status'];


$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$datetime = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );



// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    if($_FILES){
        $CHG_FILES = explode(".",$_FILES["customfile"]["name"]);
        $CHG_FILES = $datetime->format("Ymdu").'.'.$CHG_FILES[1];
        $target_dir = "../../assets/upload/";
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
            $stmt = $connect->prepare("SELECT count(*) as chk_count FROM tb_user WHERE UID = :username");
            $stmt->execute(array(":username" => $UID));
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $count = $result->chk_count;

            if($count > 0){
                if (move_uploaded_file($_FILES["customfile"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE tb_user SET FNAME = :first_name, LNAME = :last_name, STATUS_TYPE = :STATUS_TYPE, IMG = '".$CHG_FILES."', UPDATE_BY = '".$_SESSION['gid']."'
                    WHERE UID = :username";
                    $stmt= $connect->prepare($sql);

                    if($stmt->execute(array(":username" => $UID,":first_name" => $FNAME, ":last_name" => $LNAME,
                    ":STATUS_TYPE" => $STATUS_TYPE))){
                            http_response_code(200);
                            echo json_encode(array('status' => true, 'message' => 'เเก้ไขข้อมูลเรียบร้อยเเล้ว..'));
                    } else {
                            http_response_code(404);
                            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเเก้ไขข้อมูลได้!'));
                    }
                } 
            } else {
                http_response_code(403);
                echo json_encode(array('status' => false, 'message' => 'กรุณาลงทะเบียนก่อนใช้งานระบบ: '.$UID.''));
            }
        }

    } else {
        $sql = "UPDATE tb_user SET FNAME = :first_name, LNAME = :last_name, STATUS_TYPE = :STATUS_TYPE, UPDATE_BY = '".$_SESSION['gid']."' WHERE UID = :username";
        $stmt= $connect->prepare($sql);

        if($stmt->execute(array(":username" => $UID,":first_name" => $FNAME, ":last_name" => $LNAME,
        ":STATUS_TYPE" => $STATUS_TYPE))){
                http_response_code(200);
                echo json_encode(array('status' => true, 'message' => 'เเก้ไขข้อมูลเรียบร้อยเเล้ว..'));
        } else {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเเก้ไขข้อมูลได้!'));
        }
    }
    
}

?>
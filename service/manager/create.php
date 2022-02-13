<?php
header('Content-Type: application/json');
require_once '../connect.php';


$UID = $_POST['username'];
$FNAME = $_POST['first_name'];
$LNAME = $_POST['last_name'];
$PASSWORD = $_POST['password'];
$STATUS_TYPE = $_POST['status'];
$EMAIL = $_POST['email'];

$Expired_Date = date('Y-m-d',strtotime('+90 days',strtotime(str_replace('-', '-', date("Y-m-d"))))) . PHP_EOL;

$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$datetime = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
$CHG_FILES = explode(".",$_FILES["customfile"]["name"]);
$CHG_FILES = $datetime->format("Ymdu").'.'.$CHG_FILES[1];
$target_dir = "../../assets/upload/";
$target_file = $target_dir . basename($CHG_FILES);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    if ($target_file == "upload/") {
        $msg = "คุณไม่ได้อัปโหลดไฟล์เข้ามาสู่ระบบ";
        $_SESSION['message'] = $msg;
        $uploadOk = 0;
        http_response_code(403);
        echo json_encode(array('status' => false, 'message' => 'Cannot be empty.'));
    } 
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
                $stmt = $connect->prepare("SELECT * FROM tb_user WHERE UID = :username");
                $stmt->execute(array(":username" => $UID));
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $check_status = $result->STATUS_CHG;

                if($check_status == '1'){
                    if (move_uploaded_file($_FILES["customfile"]["tmp_name"], $target_file)) {
                        $sql = "UPDATE tb_user SET CREATE_BY = '".$_SESSION['gid']."', UPDATE_BY = '".$_SESSION['gid']."', STATUS_TYPE = :status, IMG = '".$CHG_FILES."', STATUS_LOGIN = 'Active', UPDATE_DATE = '".date("Y-m-d H:i:s")."', EXPIRED_DATE = '".$Expired_Date."', STATUS_CHG = '0'
                        WHERE UID = :username
                        ";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute(array(":username" => $UID,":status" => $STATUS_TYPE));

                        http_response_code(200);
                        echo json_encode(array('status' => true, 'message' => 'This username: '.$UID.' already to login again.'));
                    }
                    
                } else {
                    http_response_code(403);
                    echo json_encode(array('status' => false, 'message' => 'This username: '.$UID.' already data exist!'));
                }
        } else {
                if (move_uploaded_file($_FILES["customfile"]["tmp_name"], $target_file)) {
                        $sql = "INSERT INTO tb_user (UID,FNAME,LNAME,USERNAME,PASSWORD,STATUS_TYPE,IMG,EMAIL,STATUS_LOGIN,CREATE_BY,CREATE_DATE,UPDATE_BY,EXPIRED_DATE,UPDATE_DATE,STATUS_CHG)
                        VALUES (:username, :first_name, :last_name, :username, :password, :status, '".$CHG_FILES."', :email, 'Active', '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','".$_SESSION['gid']."','".$Expired_Date."','".date("Y-m-d H:i:s")."','0')";
                        $stmt= $connect->prepare($sql);
        
                        if($stmt->execute(array(":username" => $UID, ":first_name" => $FNAME, ":last_name" => $LNAME, ":username" => $UID, 
                        ":password" => md5($PASSWORD), ":status" => $STATUS_TYPE, ":email" => $EMAIL))){
                                http_response_code(200);
                                echo json_encode(array('status' => true, 'message' => 'Insert data completed.'));
                        } else {
                                http_response_code(404);
                                echo json_encode(array('status' => false, 'message' => 'Failed to insert data!'));
                        }
                } 
                
        }
    }
}

?>
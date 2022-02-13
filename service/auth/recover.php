<?php

header('Content-Type: application/json');
require_once '../connect.php';

$UID = $_POST['username'];
$pass_ori = $_POST['password_1'];
$pass_new = $_POST['password_2'];

$Expired_Date = date('Y-m-d',strtotime('+90 days',strtotime(str_replace('-', '-', date("Y-m-d"))))) . PHP_EOL;

if(strlen($UID) == 10){
    $UID = substr($UID,2);
} else {
    $UID = $UID;
}


$stmt = $connect->prepare("SELECT COUNT(*) AS CHECK_PASS FROM tb_user WHERE UID = :username AND PASSWORD = :password_1");
$stmt->execute(array(":password_1" => md5($pass_ori), ":username" => $UID));
$result = $stmt->fetch(PDO::FETCH_OBJ);

$count = $result->CHECK_PASS;

if($count > 0) {
    $sql = "UPDATE tb_user SET STATUS_LOGIN = 'Active', PASSWORD = :password_2, CREATE_DATE = '".date("Y-m-d H:i:s")."', EXPIRED_DATE = '".$Expired_Date."' WHERE UID = :username";
    $stmt= $connect->prepare($sql);
    if($stmt->execute(array(":password_2" => md5($pass_new), ":username" => $UID))){
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'Updating completed.'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'Change the password not completed.'));
    }

} else {
    http_response_code(403);
    echo json_encode(array('status' => false, 'message' => 'Username or Password is incorrect!'));
}

?>
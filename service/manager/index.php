<?php
header('Content-Type: application/json');
require_once '../connect.php';


$ID = $_SESSION['AD_ID'];
$STATUS = $_SESSION['AD_STATUS'];

if(strlen($ID) == 10){
    $ID = substr($ID,2);
} else {
    $ID = $ID;
}

if($STATUS == 'Admin'){
    $stmt = $connect->prepare("SELECT UID,FNAME,LNAME,USERNAME,STATUS_TYPE,EMAIL,UPDATE_DATE FROM tb_user WHERE UID = :username AND STATUS_CHG = 0");
    $stmt->execute(array(":username" => $ID));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) > 0){
        $sql = "SELECT UID,FNAME,LNAME,USERNAME,STATUS_TYPE,EMAIL,UPDATE_DATE FROM tb_user WHERE  STATUS_CHG = 0";
        $stmt = $connect->query($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => true,
            'response' => $result,
            'message' => 'success'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response = [
            'status' => true,
            'message' => 'error: No authorized on system!'
        ];
        http_response_code(401);
        echo json_encode($response);
    }
} else {
    $stmt = $connect->prepare("SELECT UID,FNAME,LNAME,USERNAME,STATUS_TYPE,EMAIL,UPDATE_DATE FROM tb_user WHERE UID = :username AND STATUS_CHG = 0");
    $stmt->execute(array(":username" => $ID));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) > 0){
        $response = [
            'status' => true,
            'response' => $result,
            'message' => 'success'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response = [
            'status' => true,
            'message' => 'error: No authorized on system!'
        ];
        http_response_code(401);
        echo json_encode($response);
    }
}

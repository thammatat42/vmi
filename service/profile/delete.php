<?php
header('Content-Type: application/json');
require_once '../connect.php';

$UID = $_POST['id'];

// $sql = "DELETE FROM tb_user WHERE UID = :username";
$sql = "UPDATE tb_user SET STATUS_LOGIN = 'Block', UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."', EXPIRED_DATE = '', STATUS_CHG = '1'
WHERE UID = :username
";
$stmt = $connect->prepare($sql);
// $stmt->bindParam(':username', $UID, PDO::PARAM_INT);

if($stmt->execute(array(":username" => $UID))) {
    $response = [
        'status' => true,
        'response' => $UID,
        'message' => 'ลบข้อมูลเรียบร้อยเเล้ว..'
    ];
    http_response_code(200);
    echo json_encode($response);

} else {
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'ไม่สามารถลบผู้ใช้ไอดี: '.$UID.' นี้ได้!'));
}


?>
<?php
header('Content-Type: application/json');
require_once '../connect.php';

$GROUP_ID = $_POST['id'];



$stmt = $connect->prepare("SELECT count(*) as chk_count FROM tb_master WHERE GROUP_ID = :GROUP_ID AND STATUS_CHG = '0'");
$stmt->execute(array(":GROUP_ID" => $GROUP_ID));
$result = $stmt->fetch(PDO::FETCH_OBJ);
$count = $result->chk_count;

if($count > 0) {
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'ไม่สามารถลบกรุ๊ป: '.$GROUP_ID.' นี้ได้เนื่องจากมาสเตอร์ใช้งานอยู่!'));
} else {
    $sql = "UPDATE tb_group_master SET UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."', STATUS_CHG = '1'
    WHERE GROUP_ID = :id AND STATUS_CHG = '0'
    ";
    $stmt = $connect->prepare($sql);

    if($stmt->execute(array(":id" => $GROUP_ID))) {
        $response = [
            'status' => true,
            'response' => $GROUP_ID,
            'message' => 'Delete Success'
        ];
        http_response_code(200);
        echo json_encode($response);
    
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'ไม่สามารถลบกรุ๊ป: '.$GROUP_ID.' นี้ได้เนื่องจากเซิฟเวอร์มีปัญหา!'));
    }
}

?>
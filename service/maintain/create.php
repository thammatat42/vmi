<?php
header('Content-Type: application/json');
require_once '../connect.php';


$CATEGORY_TH = $_POST['group_category_TH'];
$CATEGORY_ENG = $_POST['group_category_ENG'];


$sql = "INSERT INTO tb_group_master (CATEGORY_TH,CATEGORY_ENG,IMG,CREATE_BY,CREATE_DATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG) 
VALUES (:group_category_TH,:group_category_ENG,'','".$_SESSION['gid']."','".date("Y-m-d H:i:s")."', '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','0')";
$stmt= $connect->prepare($sql);

if ($CATEGORY_ENG == '' && $CATEGORY_TH == ''){
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'กรุณาใส่ข้อมูล..ไม่สามารถเพิ่มข้อมูลได้!'));
} else {
    if($stmt->execute(array(":group_category_TH" => $CATEGORY_TH,":group_category_ENG" => $CATEGORY_ENG))){
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'Insert data completed.'));
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเพิ่มข้อมูลได้!'));
    }
}




?>
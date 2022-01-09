<?php

header('Content-Type: application/json');
require_once '../connect.php';


$DATE = $_POST['date'];
$f_date = substr($DATE,0,-14);
$l_date = substr($DATE,-10,10);


if(isset($_POST['submit'])) {
    $stmt = $connect->prepare("SELECT ORDER_ID, CART_TRANS, GROUP_ID, IMG, CATEGORY_TH, SIZE, QTY, TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, DATE(ORDER_DATE) AS ORDER_DATE, STATUS_ID, DISTRIBUTE_BY, DATE(DISTRIBUTE_DATE) AS DISTRIBUTE_DATE FROM tb_products WHERE STATUS_ID = 2 AND STATUS_CHG = '0' AND DATE(DISTRIBUTE_DATE) BETWEEN :f_date and :l_date");
    $stmt->execute(array("f_date" => $f_date, "l_date" => $l_date));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) > 0) {
        $response = [
            'status' => true,
            'response' => $result,
            'message' => 'Get data success!'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        http_response_code(404);
        echo json_encode(array('status' => false, 'message' => 'ไม่พบข้อมูล!'));
    }
}
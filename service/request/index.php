<?php

header('Content-Type: application/json');
require_once '../connect.php';

// echo '<pre>';
// print_r($_POST);
// die();

if(isset($_POST['submit'])) {

    $DATE = $_POST['date'];
    $f_date = substr($DATE,0,-14);
    $l_date = substr($DATE,-10,10);

    $stmt = $connect->prepare("SELECT ORDER_ID, CART_TRANS, GROUP_ID, IMG, CATEGORY_TH, SIZE, QTY, TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, DATE(ORDER_DATE) AS ORDER_DATE, STATUS_ID, DISTRIBUTE_BY, DATE(DISTRIBUTE_DATE) AS DISTRIBUTE_DATE, RECEIVE_DATE FROM tb_products WHERE RECEIVE_DATE BETWEEN :f_date AND :l_date AND STATUS_ID = 0 AND STATUS_CHG = '0'");
    $stmt->bindParam(':f_date', $f_date);
    $stmt->bindParam(':l_date', $l_date);
    $stmt->execute();
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
        $response = [
            'status' => true,
            'message' => 'เกิดบางอย่างผิดปกติ..ไม่เจอข้อมูลในระบบ!'
        ];
        http_response_code(404);
        echo json_encode($response);
    }

} else {
    $stmt = $connect->prepare("SELECT ORDER_ID, CART_TRANS, GROUP_ID, IMG, CATEGORY_TH, SIZE, QTY, TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, DATE(ORDER_DATE) AS ORDER_DATE, STATUS_ID, DISTRIBUTE_BY, DATE(DISTRIBUTE_DATE) AS DISTRIBUTE_DATE, RECEIVE_DATE FROM tb_products WHERE RECEIVE_DATE >= DATE(CURDATE()) AND STATUS_ID = 0 AND STATUS_CHG = '0'");
    $stmt->execute();
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
        $response = [
            'status' => true,
            'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถโหลดข้อมูลได้ทั้งหมด!'
        ];
        http_response_code(404);
        echo json_encode($response);
    }
}
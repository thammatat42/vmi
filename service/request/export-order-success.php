<?php
require_once '../connect.php';

$stmt = $connect->prepare("SELECT ORDER_ID, CART_TRANS, GROUP_ID, IMG, CATEGORY_TH, SIZE, QTY, TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, DATE(ORDER_DATE) AS ORDER_DATE, STATUS_ID, DISTRIBUTE_BY, DATE(DISTRIBUTE_DATE) AS DISTRIBUTE_DATE FROM tb_products WHERE STATUS_ID = 2 AND STATUS_CHG = '0'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
if($result) {
    $delimiter = ","; 
    $fileName = "report-success-order_" . date('Y-m-d') . ".csv";

    $f = fopen('php://memory', 'w');

    $fields = array('รหัสสั่งซื้อ','ประเภทสินค้า','ไซต์','จำนวน','ผู้สั่งซื้อ','ราคา', 'วันที่ออเดอร์','ผู้จ่ายสินค้า','วันที่ส่งสินค้า', 'เเผนก', 'สถานะ');
    fputcsv($f, $fields, $delimiter); 

    foreach($result as $key => $row) {
        $count++;
        $lineData = array($row['CART_TRANS'],$row['CATEGORY_TH'],$row['SIZE'],$row['QTY'],$row['ORDER_BY'],$row['TOTAL_PRICE'],$row['ORDER_DATE'],$row['DISTRIBUTE_BY'],$row['DISTRIBUTE_DATE'],$row['DEPARTMENT'], 'Done');
        // array_walk($lineData, 'filterData');
        fputcsv($f, $lineData, $delimiter); 
    }

    fseek($f, 0); 

    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header("Content-Disposition: attachment; filename=\"$fileName\""); 
    header('Content-Transfer-Encoding: binary');
    header("Pragma: public");
    header('Expires: 0');
    echo "\xEF\xBB\xBF";

    fpassthru($f); 
    
} else {
    header('Location: ../../pages/request/success.php');
}
exit();


?>
<?php
require_once '../connect.php';


$stmt = $connect->prepare("SELECT * FROM v_order_export_excel");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
if($result) {
    $delimiter = ","; 
    $fileName = "send-order_" . date('Y-m-d') . ".csv";

    $f = fopen('php://memory', 'w');

    $fields = array('กรุ๊ป','ประเภท','ไซต์','คลังสินค้า','ความต้องการ (Building 1)','ความต้องการ (Building 3)', 'รวมสินค้าที่ต้องการทั้งหมด','คลังสินค้าคงเหลือ','สถานะ','วันที่ออเดอร์','วันที่ส่งสินค้า');
    fputcsv($f, $fields, $delimiter); 

    foreach($result as $key => $row) {
        $count++;
        $lineData = array($row['GROUP_ID'],$row['CATEGORY_TH'],$row['SIZE'],$row['PREVIOUS_STOCK'],$row['DEMAND_BUILDING_1'],$row['DEMAND_BUILDING_3'],$row['TOTAL_DEMAND'],$row['REMAIN_STOCK'],$row['STATUS'],$row['ORDER_DATE'],$row['RECEIVE_DATE']);
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
    header('Location: ../../pages/maintain/stock.php');
}
exit();


?>
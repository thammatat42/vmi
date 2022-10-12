<?php
require_once '../connect.php';


$stmt = $connect->prepare("SELECT GROUP_ID,CATEGORY_TH, IMG, SIZE, STOCK+DEMAND AS PREVIOUS_STOCK, DEMAND, REMAIN_STOCK, STATUS FROM (SELECT A.GROUP_ID,A.CATEGORY_TH,A.IMG,A.SIZE, CASE WHEN A.C_STOCK < 0 THEN 0 ELSE A.C_STOCK END AS STOCK, COUNT(B.QTY) AS DEMAND,A.C_STOCK AS REMAIN_STOCK,CASE WHEN A.C_STOCK <= 0 THEN 'NG' ELSE 'OK' END AS STATUS 
FROM tb_master as A LEFT JOIN
tb_products AS B ON A.GROUP_ID = B.GROUP_ID AND A.SIZE = B.SIZE
WHERE A.STATUS_CHG = 0 GROUP BY SIZE,GROUP_ID
) ALERT_STOCK ORDER BY GROUP_ID,SIZE");

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
if($result) {
    $delimiter = ","; 
    $fileName = "manage-stock_" . date('Y-m-d') . ".csv";

    $f = fopen('php://memory', 'w');

    $fields = array('กรุ๊ป','ประเภท','ไซต์','คลังสินค้า','ความต้องการ','คลังสินค้าคงเหลือ','สถานะ');
    fputcsv($f, $fields, $delimiter); 

    foreach($result as $key => $row) {
        $count++;
        $lineData = array($row['GROUP_ID'],$row['CATEGORY_TH'],$row['SIZE'],$row['PREVIOUS_STOCK'],$row['DEMAND'],$row['REMAIN_STOCK'],$row['STATUS']);
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
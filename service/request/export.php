<?php
require_once '../connect.php';

$stmt = $connect->prepare("SELECT A.ORDER_BY, CONCAT(B.FNAME, ' ', B.LNAME) AS FULL_NAME, B.POSITION, B.DEPARTMENT, DATE(A.DISTRIBUTE_DATE) AS DISTRIBUTE_DATE, '' AS PAYMENT_DATE, SUM(A.TOTAL_PRICE) AS TOTAL_PRICE
FROM tb_products AS A INNER JOIN 
tb_all_user AS B ON A.ORDER_BY = B.UID
WHERE A.TOTAL_PRICE > 0 AND A.STATUS_ID = 2
GROUP BY ORDER_BY");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
if($result) {
    $delimiter = ","; 
    $fileName = "report_payment_" . date('Y-m-d') . ".csv";

    $f = fopen('php://memory', 'w');


    $fields = array('No','EN','Name-Surname','Level','Department','Ditribute Date','Payment Date','Total Cost');
    fputcsv($f, $fields, $delimiter); 

    foreach($result as $key => $row) {
        $count++;
        $lineData = array($count, $row['ORDER_BY'],$row['FULL_NAME'],$row['POSITION'],$row['DEPARTMENT'],$row['DISTRIBUTE_DATE'],$row['PAYMENT_DATE'],$row['TOTAL_PRICE']);
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
<?php
    header('Content-Type: application/json');
    require_once '../../service/connect.php';

    $sql = "SELECT CATEGORY_TH, SUM(QTY) AS qty
    FROM tb_products
    WHERE YEAR(ORDER_DATE) = YEAR(NOW()) AND STATUS_ID = 2
    GROUP BY CATEGORY_TH,DATE_FORMAT(ORDER_DATE, '%M')";
    $stmt_sales = $connect->query($sql);
    $stmt_sales->execute();
    $result_sales = $stmt_sales->fetchAll(PDO::FETCH_ASSOC);
 
        
    foreach($result_sales as $key => $row) {
        $arr = array(
            'value' => $row['qty'],
            'category' => $row['CATEGORY_TH']
        );

        $response[] = $arr;

    }

    echo json_encode($response, JSON_NUMERIC_CHECK);
?>
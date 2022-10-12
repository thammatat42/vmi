<?php
    header('Content-Type: application/json');
    require_once '../../service/connect.php';

    $sql = "SELECT month,
	SUM(CASE WHEN CATEGORY_TH = 'เสื้อกั๊กสีฟ้า' THEN qty END) VEST_BLUE,
    SUM(CASE WHEN CATEGORY_TH = 'เสื้อกั๊กสีเทา' THEN qty END) VEST_GRAY,
    SUM(CASE WHEN CATEGORY_TH = 'รองเท้า ESD' THEN qty END) ESD_SHOES,
    SUM(CASE WHEN CATEGORY_TH = 'รองเท้าผ้าใบ' THEN qty END) NANYANG_SHOES,
    SUM(CASE WHEN CATEGORY_TH = 'ชุดช่าง' THEN qty END) MAINTENANCE_SUIT,
    SUM(CASE WHEN CATEGORY_TH = 'ชุดคลุมท้องสีฟ้า' THEN qty END) BLUE_MOTERNILY_CLOTHES,
    SUM(CASE WHEN CATEGORY_TH = 'ชุดคลุมท้องสีเทา' THEN qty END) GRAY_MOTERNILY_CLOTHES,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (เขียวอ่อน)' THEN qty END) CAP_GREEN,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (เขียวเข้ม)' THEN qty END) CAP_DARK_GREEN,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (ขาว)' THEN qty END) CAP_WHITE,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (สีเหลือง)' THEN qty END) CAP_YELLOW,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (สีชมพู)' THEN qty END) CAP_PINK,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (สีน้ำตาล)' THEN qty END) CAP_BROWN,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (สีดำ)' THEN qty END) CAP_BLACK,
    SUM(CASE WHEN CATEGORY_TH = 'หมวก (สีฟ้า)' THEN qty END) CAP_BLUE,
    SUM(CASE WHEN CATEGORY_TH = 'สายคล้องบัตร' THEN qty END) CARD_LANYARD
    FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY MONTH(ORDER_DATE) ORDER BY MONTH(ORDER_DATE)) as ID FROM v_order_list_monthly) t
    GROUP BY month ORDER BY MONTH(ORDER_DATE) ASC";
    $stmt_sales = $connect->query($sql);
    $stmt_sales->execute();
    $result_sales = $stmt_sales->fetchAll(PDO::FETCH_ASSOC);

        
        
    foreach($result_sales as $key => $row) {
        $arr = array(
            'month' => $row['month'],
            'VEST_BLUE' => $row['VEST_BLUE'],
            'VEST_GRAY' => $row['VEST_GRAY'],
            'ESD_SHOES' => $row['ESD_SHOES'],
            'NANYANG_SHOES' => $row['NANYANG_SHOES'],
            'MAINTENANCE_SUIT' => $row['MAINTENANCE_SUIT'],
            'BLUE_MOTERNILY_CLOTHES' => $row['BLUE_MOTERNILY_CLOTHES'],
            'GRAY_MOTERNILY_CLOTHES' => $row['GRAY_MOTERNILY_CLOTHES'],
            'CAP_GREEN' => $row['CAP_GREEN'],
            'CAP_DARK_GREEN' => $row['CAP_DARK_GREEN'],
            'CAP_WHITE' => $row['CAP_WHITE'],
            'CAP_YELLOW' => $row['CAP_YELLOW'],
            'CAP_PINK' => $row['CAP_PINK'],
            'CAP_BROWN' => $row['CAP_BROWN'],
            'CAP_BLACK' => $row['CAP_BLACK'],
            'CAP_BLUE' => $row['CAP_BLUE'],
        );

        $response[] = $arr;

    }

    echo json_encode($response, JSON_NUMERIC_CHECK);
?>
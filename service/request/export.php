<?php
header('Content-Type: application/json');
require_once '../connect.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
$date = date('Y-m-d');
/* Grand Total */
$stmt_total = $connect->prepare("SELECT SUM(TOTAL_PRICE) AS TOTAL FROM tb_products
WHERE TOTAL_PRICE > 0 AND STATUS_ID = 2");
$stmt_total->execute();
$result_grand_total = $stmt_total->fetch(PDO::FETCH_OBJ);
$GRAND_TOTAL = $result_grand_total->TOTAL;


/* Excel Data */
$stmt = $connect->prepare("SELECT A.ORDER_BY, CONCAT(B.FNAME, ' ', B.LNAME) AS FULL_NAME, B.POSITION, B.DEPARTMENT, DATE(A.DISTRIBUTE_DATE) AS DISTRIBUTE_DATE, '' AS PAYMENT_DATE, SUM(A.TOTAL_PRICE) AS TOTAL_PRICE
FROM tb_products AS A INNER JOIN 
tb_all_user AS B ON A.ORDER_BY = B.UID
WHERE A.TOTAL_PRICE > 0 AND A.STATUS_ID = 2
GROUP BY ORDER_BY");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($result) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $fileName = "report_payment_" . date('Y-m-d');

    $styleArray = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        ],
        'borders' => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => 'FFA0A0A0',
            ],
            'endColor' => [
                'argb' => 'FFFFFFFF',
            ],
        ],
    ];
    
    $sheet->setCellValue('A1', 'NO');
    $sheet->getStyle('A1')->applyFromArray($styleArray);
    $sheet->setCellValue('B1', 'EN');
    $sheet->setCellValue('C1', 'Name-Surname');
    $sheet->setCellValue('D1', 'Level');
    $sheet->setCellValue('E1', 'Department');
    $sheet->setCellValue('F1', 'Ditribute Date');
    $sheet->setCellValue('G1', 'Payment Date');
    $sheet->setCellValue('H1', 'Total Cost');

    $count = 0;
    $rowCount = 2;
    $rowCount_total = 3;
    foreach($result as $key => $row) {
        $count++;
        $sheet->setCellValue('G'.$rowCount_total, 'Grand Total');
        $sheet->setCellValue('H'.$rowCount_total, $GRAND_TOTAL);

        $sheet->setCellValue('A' . $rowCount, $count);
        $sheet->setCellValue('B' . $rowCount, $row['ORDER_BY']);
        $sheet->setCellValue('C' . $rowCount, $row['FULL_NAME']);
        $sheet->setCellValue('D' . $rowCount, $row['POSITION']);
        $sheet->setCellValue('E' . $rowCount, $row['DEPARTMENT']);
        $sheet->setCellValue('F' . $rowCount, $row['DISTRIBUTE_DATE']);
        $sheet->setCellValue('G' . $rowCount, $row['PAYMENT_DATE']);
        $sheet->setCellValue('H' . $rowCount, $row['TOTAL_PRICE']);
        $rowCount++;
        $rowCount_total++;
        
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
    $fileName = 'excel/'.$fileName.'.csv';
    $writer->save($fileName);
    $filepath = file_get_contents($fileName);


    $filename2 = 'excel/report_payment_' .$date. '.csv';
    $header = header('Location: '.$filename2.'');




    

} else {
    header('Location: ../../pages/request/success.php');
}





exit();



?>
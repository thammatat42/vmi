<?php
header('Content-Type: application/json');
require_once '../connect.php';

$response = [
    'status' => true,
    'response' => [
        'label' => 'ออกรายงาน',
        'labels' => ['01', '02', '03', '04', '05', '06', '07','08', '09', '10', '11', '12'], 
        'results' => [2000, 1300, 1800, 1000, 1500, 2600, 3250, 2000, 1300, 4100, 1000, 1500]
    ],
    'message' => 'OK'
];
http_response_code(200);
echo json_encode($response);

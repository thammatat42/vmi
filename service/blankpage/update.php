<?php
/**
 **** AppzStory Admin Ajax ****
 * Update 
 * 
 * @link https://appzstory.dev
 * @author Yothin Sapsamran (Jame AppzStory Studio)
 */
header('Content-Type: application/json');
require_once '../connect.php';
/**
 |--------------------------------------------------------------------------
 | เขียนโค้ด Update  SQL ตัวอย่าง
 | 'UPDATE table SET field1 = :var1, field2= :var2 WHERE id = :id "'
 |--------------------------------------------------------------------------
*/
$response = [
    'status' => true,
    'message' => 'Update Success'
];
http_response_code(200);
echo json_encode($response);

?>
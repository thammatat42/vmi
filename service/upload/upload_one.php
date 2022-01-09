<?php
header('Content-Type: application/json');
require_once '../connect.php';
require_once '../../service/db-config.php';



// print_r($_POST);
// print_r($_FILES);
// print_r($_SESSION);
// die();

if(isset($_POST['upload_form'])) {

    $fileName = "";

    // if there is any file
    if(isset($_FILES['customfile'])){

        // reading tmp_file name
        $fileName = $_FILES["customfile"]["tmp_name"];
    }

    $counter = 0;	 


    // if file size is not empty
    if (isset($_FILES["customfile"]) && $_FILES["customfile"]["size"] > 0) {   

        $file = fopen($fileName, "r");

        // eliminating the first row of CSV file
        fgetcsv($file);  

        $result = '';

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) { 

            $counter++;	   

            // assigning csv column to a variable
            $UID = $column[0];

            $FNAME  = $column[1];
            
            $LNAME  = $column[2];

            $USERNAME = $column[3];
            
            $PASSWORD =	$column[4];
            
            $STATUS_TYPE = $column[5];

            $IMG = 'no_img.jpg';

            $EMAIL = $column[7];

            $STATUS_CHG = $column[14];

            $Expired_Date = date('Y-m-d',strtotime('+90 days',strtotime(str_replace('-', '-', date("Y-m-d"))))) . PHP_EOL;
            
            $stmt = "SELECT count(*) as chk_count FROM tb_user WHERE UID = '$UID' AND STATUS_CHG = '0'";
            $result_check = $conn->query($stmt);
            $count = $result_check->fetch_object();
            $count_check = $count->chk_count;

            if($count_check > 0) {

            } else {
                // inserting values into the table
                $sql =	"INSERT INTO tb_user (UID,FNAME,LNAME,USERNAME,PASSWORD,STATUS_TYPE,IMG,EMAIL,STATUS_LOGIN,CREATE_BY,CREATE_DATE,EXPIRED_DATE,STATUS_CHG) 
                VALUES ('$UID', '$FNAME', '$LNAME', '$USERNAME', '$PASSWORD', '$STATUS_TYPE', '$IMG', '$EMAIL', 'Active', '".$_SESSION['gid']."', '".date("Y-m-d H:i:s")."','$Expired_Date', '$STATUS_CHG') ";
                $result = $conn->query($sql);

            }
            
        }

        if($result) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'เพิ่มข้อมูลเรียบร้อยเเล้ว..'));
        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..มีข้อมูลอยู่เเล้ว!'));
        }
        
    }
}




?>
<?php
header('Content-Type: application/json');
require_once '../connect.php';
require_once '../../service/db-config.php';


// echo '<pre>';
// print_r($_POST);
// print_r($_FILES);
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

        $stmt = "TRUNCATE TABLE tb_production_calendar";
        $result_check = $conn->query($stmt);

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) { 

            $counter++;	   

            // assigning csv column to a variable
            $CALENDAR_ID = $column[0];

            $F_YEAR  = $column[1];
            
            $CALENDAR_DATE  = $column[2];

            $HOLIDAY_FLAG = $column[3];

            

            $sql =	"INSERT INTO tb_production_calendar (CALENDAR_ID,F_YEAR,CALENDAR_DATE,HOLIDAY_FLAG,UPDATE_BY,UPDATE_DATE,STATUS_CHG) 
                    VALUES ('$CALENDAR_ID', '$F_YEAR', '$CALENDAR_DATE', '$HOLIDAY_FLAG', '".$_SESSION['gid']."', '".date("Y-m-d H:i:s")."', 0) ";
            $result = $conn->query($sql);
            
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
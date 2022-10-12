<?php
header('Content-Type: application/json');
require_once '../connect.php';
require_once '../../service/db-config.php';
require("../../plugins/PHPExcel/Classes/PHPExcel/IOFactory.php");

if(isset($_POST['upload_stock'])) {

    $fileName = "";

    // if there is any file
    if(isset($_FILES['customfile'])){

        // reading tmp_file name
        $fileName = $_FILES["customfile"]["tmp_name"];

        $count_column = 4;
        $column_name = array("GROUP_ID", "CATEGORY_TH", "SIZE", "STOCK");
        
    }

    $counter = 0;	

    if (isset($_FILES["customfile"]) && $_FILES["customfile"]["size"] > 0) {   
        $file_array = explode(".", $_FILES["customfile"]["name"]);

        if ($file_array[1] == "xlsx" || $file_array[1] == "csv" || $file_array[2] == "xlsx" || $file_array[2] == "csv") {
            $object = PHPExcel_IOFactory::load($_FILES["customfile"]["tmp_name"]);
            
            $array_failed = [];
            $array_success_group_id = [];
            $array_success_cate = [];
            $array_success_size = [];
            $array_success_input_number = [];
            $array_success_total_group = [];
            $array_success_total_master = [];
            $adjust_inventory = 0;
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                $RowSkip = 2;

                for ($row = $RowSkip; $row <= $highestRow; $row++) {
                    for ($column_number = 0; $column_number < $count_column; $column_number++) {
                        $data_in_file = $worksheet->getCellByColumnAndRow($column_number, $row)->getValue();
                        $check_value_excel[$column_number] = $data_in_file;
                    }
    
                    $value_excel = array_combine($column_name, $check_value_excel);
    
                    $arr_value = ('\'' . implode('\',\'', array_values($value_excel)) . '\'');

                    $GROUP_ID = $value_excel['GROUP_ID'];
                    $CATEGORY_TH = $value_excel['CATEGORY_TH'];
                    $SIZE = $value_excel['SIZE'];
                    $INPUT_NUMBER = $value_excel['STOCK'];

                    if($INPUT_NUMBER == "") {
                        $INPUT_NUMBER = 0;
                    }


                    /* เช็ค max stock */
                    $stmt_check_max = $connect->prepare("SELECT GROUP_ID,CATEGORY_TH,CATEGORY_ENG,IMG,C_STOCK,TOTAL_MAX,TOTAL_MIN,CREATE_BY,CREATE_DATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG FROM (
                        SELECT A.*,SUM(B.MAX_STOCK) AS TOTAL_MAX, SUM(B.MIN_STOCK) AS TOTAL_MIN FROM tb_master AS A INNER JOIN tb_stock_master AS B ON A.GROUP_ID = B.GROUP_ID  AND A.SIZE = B.SIZE
                        WHERE B.GROUP_ID = :GROUP_ID AND B.SIZE = :SIZE AND A.STATUS_CHG = '0' AND B.STATUS_CHG = 0 GROUP BY B.GROUP_ID
                    ) GROUP_MASTER");
                    $stmt_check_max->bindParam(':GROUP_ID', $GROUP_ID);
                    $stmt_check_max->bindParam(':SIZE', $SIZE);
                    $stmt_check_max->execute();
                    $result_max = $stmt_check_max->fetch(PDO::FETCH_OBJ);
                    $MAX_STOCK = $result_max->TOTAL_MAX;

                    
                   

                    $TOTAL_GROUP_MASTER_STOCK = $INPUT_NUMBER;

                    /****************************************************************************************************************************/
                    /****************************************************************************************************************************/

                    /* ผลลัพธ์ stock ของ tb_master */
                    $stmt2 = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
                    $stmt2->bindParam(':id', $GROUP_ID);
                    $stmt2->bindParam(':SIZE', $SIZE);
                    $stmt2->execute();
                    $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
                    $MASTER_C_STOCK = $result2->C_STOCK;
                    $TOTAL_MASTER_STOCK = $MASTER_C_STOCK + $INPUT_NUMBER;
                    /* จบ tb_master */
                    

                    if($TOTAL_MASTER_STOCK > $MAX_STOCK) {
                        //รหัส code error inventory 101 = คลังสินค้าไม่พอ
                        $array_failed[] = $GROUP_ID. ':' . $SIZE;
                        $adjust_inventory = 101;

                    } else {
                        //เก็บค่าใส่ array เพื่อที่ไป update
                        $array_success[] = $GROUP_ID. ':' . $SIZE;
                        $array_success_group_id[] = $GROUP_ID;
                        $array_success_cate[] = $CATEGORY_TH;
                        $array_success_size[] = $SIZE;
                        $array_success_input_number[] = $INPUT_NUMBER;
                        $array_success_total_group[] = $TOTAL_GROUP_MASTER_STOCK;
                        $array_success_total_master[] = $TOTAL_MASTER_STOCK;
                    }

                    
                }
            }

            // echo '<pre>';
            // print_r($array_success_total_group);
            // print_r($array_success_group_id);
            // die();
            

            if($adjust_inventory == 101) { 
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ (101): สินค้าควร adjust: '.implode(',', $array_failed)));
            } else {
                $count_success = count($array_success_group_id);
                for($i=0; $i<$count_success; $i++) {

                    $sql = "INSERT INTO tb_update_stock_history (GROUP_ID,CATEGORY_TH,SIZE,STOCK_UPDATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG)
                                VALUES (:id, :CATEGORY_TH, :SIZE, :STOCK_UPDATE, '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','0')";
                    $stmt = $connect->prepare($sql);
                    $stmt->bindParam(':id', $array_success_group_id[$i]);
                    $stmt->bindParam(':CATEGORY_TH', $array_success_cate[$i]);
                    $stmt->bindParam(':SIZE', $array_success_size[$i]);
                    $stmt->bindParam(':STOCK_UPDATE', $array_success_input_number[$i]);
                    $INSERT_STOCK_HISTORY = $stmt->execute();

                        
                    $sql2 = "UPDATE tb_master
                    SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                    WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
                    $stmt2 = $connect->prepare($sql2);
                    $stmt2->bindParam(':id', $array_success_group_id[$i]);
                    $stmt2->bindParam(':C_STOCK', $array_success_total_master[$i]);
                    $stmt2->bindParam(':SIZE', $array_success_size[$i]);
                    $UPDATE_MASTER = $stmt2->execute();

                    /* ผลลัพธ์ stock ของ tb_group_master */
                    $stmt = $connect->prepare("SELECT C_STOCK FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
                    $stmt->bindParam(':id', $array_success_group_id[$i]);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_OBJ);
                    $C_STOCK = $result->C_STOCK;
                    $TOTAL_GROUP_MASTER_STOCK = $C_STOCK + $array_success_total_group[$i];

                    /* จบ tb_group_master */

                    $sql3 = "UPDATE tb_group_master
                    SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                    WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
                    $stmt3 = $connect->prepare($sql3);
                    $stmt3->bindParam(':id', $array_success_group_id[$i]);
                    $stmt3->bindParam(':C_STOCK', $TOTAL_GROUP_MASTER_STOCK);
                    $UPDATE_GROUP_MASTER = $stmt3->execute();




                    // $sql = "INSERT INTO tb_update_stock_history (GROUP_ID,CATEGORY_TH,SIZE,STOCK_UPDATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG)
                    //             VALUES (:id, :CATEGORY_TH, :SIZE, :STOCK_UPDATE, '".$_SESSION['gid']."','".date("Y-m-d H:i:s")."','0')";
                    // $stmt = $connect->prepare($sql);
                    // $stmt->bindParam(':id', $GROUP_ID);
                    // $stmt->bindParam(':CATEGORY_TH', $CATEGORY_TH);
                    // $stmt->bindParam(':SIZE', $SIZE);
                    // $stmt->bindParam(':STOCK_UPDATE', $INPUT_NUMBER);
                    // $INSERT_STOCK_HISTORY = $stmt->execute();

                        
                    // $sql2 = "UPDATE tb_master
                    // SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                    // WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
                    // $stmt2 = $connect->prepare($sql2);
                    // $stmt2->bindParam(':id', $GROUP_ID);
                    // $stmt2->bindParam(':C_STOCK', $TOTAL_MASTER_STOCK);
                    // $stmt2->bindParam(':SIZE', $SIZE);
                    // $UPDATE_MASTER = $stmt2->execute();

                    // $sql3 = "UPDATE tb_group_master
                    // SET C_STOCK = :C_STOCK, UPDATE_BY = '".$_SESSION['gid']."', UPDATE_DATE = '".date("Y-m-d H:i:s")."'
                    // WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
                    // $stmt3 = $connect->prepare($sql3);
                    // $stmt3->bindParam(':id', $GROUP_ID);
                    // $stmt3->bindParam(':C_STOCK', $TOTAL_GROUP_MASTER_STOCK);
                    // $UPDATE_GROUP_MASTER = $stmt3->execute();
                }

                if($UPDATE_MASTER && $UPDATE_GROUP_MASTER && $INSERT_STOCK_HISTORY){
                    
                    http_response_code(200);
                    echo json_encode(array('status' => true, 'message' => 'อัพเดทสต๊อกเรียบร้อยเเล้ว..'));
                } else {
                    http_response_code(404);
                    echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถทำการอัพเดทสต็อกได้!'));
                }
            }
            
        }
        
    }

}
?>
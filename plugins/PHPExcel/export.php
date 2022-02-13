<?php   

    SESSION_START();
    $root = $_SERVER['DOCUMENT_ROOT'];
    include $root . "/COMMON_IF/func/INIT.int";
    // include $root . "/COMMON_IF/func/MSSQL.func";
    include $root . "/COMMON_IF/db/COMMON_IF.conn";

    function MSSQL_GETDATA_SQL($conn,$sql)
    {
        $result	= sqlsrv_query($conn,$sql,array(SQLSRV_PHPTYPE_STRING('UTF-8'))) or die(print_r( sqlsrv_errors(), true));
        while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
        {
            $rows[] = $row;
        }
        return $rows;
    }

    // require_once 'item.php';
    require("Classes/PHPExcel/IOFactory.php");
    date_default_timezone_set("Asia/Bangkok");

    // print_r($_FILES);
    // die();

    if (!empty($_FILES["excel_file"])) {

        // TABLE IS TABLE THAT YPU SELECT TO INPUT DATA
        // $table = $_POST["table"];
        // $error = $_POST["check"];
        // $obj2 = $_POST['myData'];
        // $MEMBER_ID = $_POST['check_biz'];

        $count_column = 1;
        $column_name = array("SEQ_NO");

        $file_array = explode(".", $_FILES["excel_file"]["name"]);
        // print_r($file_array);
        if ($file_array[1] == "xlsx" || $file_array[1] == "csv" || $file_array[2] == "xlsx" || $file_array[2] == "csv") {

            // load data excel
            $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]);

            foreach ($object->getWorksheetIterator() as $worksheet) {
                print_r($worksheet);
                // echo count($worksheet);
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $count = 0;
                $RowSkip = 2; //row excel start 2
                $query_db = array();

                print_r($highestRow);

                for ($row = $RowSkip; $row <= $highestRow; $row++) {
                    
                    for ($column_number = 0; $column_number < $count_column; $column_number++) {
                        $data_in_file = $worksheet->getCellByColumnAndRow($column_number, $row)->getValue();
                        $check_value_excel[$column_number] = $data_in_file;
                        // print_r($data_in_file);
                    }

                    
                    $value_excel = array_combine($column_name, $check_value_excel);
                    // $value_excel['ID'] = NULL;
                    // $value_excel['LastUpdate'] = date("Y-m-d H:i:s");

                    // echo $value_excel['BIZ'];
                    // echo $BIZ;
                    // if ($value_excel['BIZ'] !== $BIZ) {
                    //     $error_biz[$row - 1] = 'BIZ';
                    //     $biz = $error_biz;
                    //     $row_error[] = $row - 1;
                    //     $query_db = '';
                    // }

                    

                    $arr_value = ('\'' . implode('\',\'', array_values($value_excel)) . '\'');
                    
                    if ($value_excel['BIZ'] == "ALL") {
                        // $query_db[] = "INSERT INTO `$table` VALUES ($arr_value)";
                        array_push($query_db, "INSERT INTO [DATAWAREHOUSE].[dbo].[COMMON_IF_CANCEL]([SEQ_NO]) VALUES ($arr_value)");
                    } else {
                        array_push($query_db, "INSERT INTO [DATAWAREHOUSE].[dbo].[COMMON_IF_CANCEL]([SEQ_NO]) VALUES ($arr_value)");
                    }
                }
            }
            
            // echo $test;
            // echo $row;  
            // print_r($arr_value);
            // print_r($value_excel);
            // print_r($query_db);

            // if (isset($row_error)) {
            //     $unique_error = array_unique($row_error);
            //     foreach ($unique_error as $row) {

            //         if (isset($min[$row])) {
            //             // $min_data = implode(', ', array_values($min));
            //             $min_data = $min[$row];
            //             $error_data[] = $min_data;
            //         }
            //         if (isset($max[$row])) {
            //             // $max_data = implode(', ', array_values($max));
            //             $max_data = $max[$row];
            //             $error_data[] = $max_data;
            //         }
            //         if (isset($biz[$row])) {
            //             $biz_data = $biz[$row];
            //             // $biz_data = implode(', ', array_values($biz_r));
            //             $error_data[] = $biz_data;
            //         }
            //         $str_error_data = implode(', ', array_values($error_data));
            //         $data[] = "<br>row " . $row . " error column (" . $str_error_data . ")";
            //         $error_data = NULL;
            //     }
            //     echo json_encode($data);
            // } else {
                // $data = "success";
                // echo json_encode($data);
                foreach ($query_db as $key) {
                    MSSQL_GETDATA_SQL($DATAWAREHOUSE, $key);
                }
            // }
        }
    }
?>
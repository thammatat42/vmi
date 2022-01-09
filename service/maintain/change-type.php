<?php 
header('Content-Type: application/json');
require_once '../connect.php';

if (isset($_POST['function']) && $_POST['function'] == 'emp_type') {
    $EMP_ID = $_POST['id'];

    if($EMP_ID == 0) {
        $result_level = $connect->query("SELECT DISTINCT TYPE_ID,EMP_POSITION FROM tb_emp_group_master ORDER BY 2");

        echo '<option value disabled selected>กรุณาเลือกระดับพนักงาน</option>';
        echo '<option value="All">All</option>';
        echo '<option value="M5/I5 up">M5/I5 up</option>';
        foreach ($result_level as $row) {
            echo '<option value="'.$row['EMP_POSITION'].'">'.$row['EMP_POSITION'].'</option>';
            
        }
    } else {
        $stmt = $connect->prepare("SELECT * FROM tb_emp_group_master WHERE GROUP_ID = :id");
        $stmt->execute(array(":id" => $EMP_ID));
    
        echo '<option value disabled selected>กรุณาเลือกระดับพนักงาน</option>';
        echo '<option value="All">All</option>';
        echo '<option value="M5/I5 up">M5/I5 up</option>';
        foreach ($stmt as $row) {
            echo '<option value="'.$row['EMP_POSITION'].'">'.$row['EMP_POSITION'].'</option>';
            
        }
    }
    exit();
}

?>
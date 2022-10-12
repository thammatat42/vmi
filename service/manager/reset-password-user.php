<?php
header('Content-Type: application/json');
require_once '../connect.php';

if(isset($_POST['uid']) && isset($_POST['remark_log'])) {
    $UID = $_POST['uid'];
    $REMARK_LOG = $_POST['remark_log'];
    $ADMIN = $_SESSION['gid'];
    $date = date('Y-m-d H:i:s');

    $stmt_check_user = $connect->prepare("SELECT * FROM tb_user WHERE UID = :UID and STATUS_CHG = 0 and cast(NOW() as Date) < EXPIRED_DATE");
    $stmt_check_user->bindParam(':UID', $UID);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->fetch(PDO::FETCH_OBJ);

    if(!empty($result_check_user)) {
        $UID_DB = $result_check_user->UID;
        $OLD_PASSWORD = $result_check_user->PASSWORD;
        $NEW_PASSWORD = md5($UID_DB);
        $status_chg = 0;

        $stmt_insert_db_log = $connect->prepare("INSERT INTO tb_log_reset_password (UID, OLD_PASSWORD, NEW_PASSWORD, RESET_BY, RESET_DATE, REMARK, STATUS_CHG) VALUES (:UID, :OLD_PASSWORD, :NEW_PASSWORD, :RESET_BY, :RESET_DATE, :REMARK, :STATUS_CHG)");
        $stmt_insert_db_log->bindParam(':UID', $UID_DB);
        $stmt_insert_db_log->bindParam(':OLD_PASSWORD', $OLD_PASSWORD);
        $stmt_insert_db_log->bindParam(':NEW_PASSWORD', $NEW_PASSWORD);
        $stmt_insert_db_log->bindParam(':RESET_BY', $ADMIN);
        $stmt_insert_db_log->bindParam(':RESET_DATE', $date);
        $stmt_insert_db_log->bindParam(':REMARK', $REMARK_LOG);
        $stmt_insert_db_log->bindParam(':STATUS_CHG', $status_chg);
        $INSERT_LOG = $stmt_insert_db_log->execute();

        if($INSERT_LOG) {
            $stmt_update_db_user = $connect->prepare("UPDATE tb_user SET PASSWORD = :NEW_PASSWORD WHERE UID = :UID AND STATUS_CHG = 0");
            $stmt_update_db_user->bindParam(':UID', $UID_DB);
            $stmt_update_db_user->bindParam(':NEW_PASSWORD', $NEW_PASSWORD);
            $UPDATE_USER = $stmt_update_db_user->execute();

            if($UPDATE_USER) {
                http_response_code(200);
                echo json_encode(array('status' => true, 'message' => 'ทำการเปลี่ยนรหัสผ่านเรียบร้อยแล้ว'));
            }
        }

    } else {
        //Case รหัสผ่านหมดอายุทำการ reset ให้กลับมาเป็นผ่าน default เเละ อัพเดทวันหมดอายุให้
        $stmt_check_user = $connect->prepare("SELECT * FROM tb_user WHERE UID = :UID and STATUS_CHG = 0");
        $stmt_check_user->bindParam(':UID', $UID);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->fetch(PDO::FETCH_OBJ);

        if(!empty($result_check_user)) {
            $UID_DB = $result_check_user->UID;
            $OLD_PASSWORD = $result_check_user->PASSWORD;
            $NEW_PASSWORD = md5($UID_DB);
            $Expired_Date = date('Y-m-d',strtotime('+90 days',strtotime(str_replace('-', '-', date("Y-m-d"))))) . PHP_EOL;
            $status_chg = 1; //รหัสผ่านหมดอายุ

            $stmt_insert_db_log = $connect->prepare("INSERT INTO tb_log_reset_password (UID, OLD_PASSWORD, NEW_PASSWORD, RESET_BY, RESET_DATE, REMARK, STATUS_CHG) VALUES (:UID, :OLD_PASSWORD, :NEW_PASSWORD, :RESET_BY, :RESET_DATE, :REMARK, :STATUS_CHG)");
            $stmt_insert_db_log->bindParam(':UID', $UID_DB);
            $stmt_insert_db_log->bindParam(':OLD_PASSWORD', $OLD_PASSWORD);
            $stmt_insert_db_log->bindParam(':NEW_PASSWORD', $NEW_PASSWORD);
            $stmt_insert_db_log->bindParam(':RESET_BY', $ADMIN);
            $stmt_insert_db_log->bindParam(':RESET_DATE', $date);
            $stmt_insert_db_log->bindParam(':REMARK', $REMARK_LOG);
            $stmt_insert_db_log->bindParam(':STATUS_CHG', $status_chg);
            $INSERT_LOG = $stmt_insert_db_log->execute();

            if($INSERT_LOG) {
                $stmt_update_db_user = $connect->prepare("UPDATE tb_user SET PASSWORD = :NEW_PASSWORD, EXPIRED_DATE = '".$Expired_Date."' WHERE UID = :UID AND STATUS_CHG = 0");
                $stmt_update_db_user->bindParam(':UID', $UID_DB);
                $stmt_update_db_user->bindParam(':NEW_PASSWORD', $NEW_PASSWORD);
                $UPDATE_USER = $stmt_update_db_user->execute();
    
                if($UPDATE_USER) {
                    http_response_code(200);
                    echo json_encode(array('status' => true, 'message' => 'ทำการเปลี่ยนรหัสผ่านเรียบร้อยแล้ว'));
                }
            }

        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'));
        }
    }
}



?>
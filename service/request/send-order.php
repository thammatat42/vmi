<?php

header('Content-Type: application/json');
require_once '../connect.php';


$stmt = $connect->prepare("SELECT * FROM tb_order_trans WHERE ORDER_BY = '".$_SESSION['gid']."' AND STATUS_CHG = '0'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


$ADD_TRANS = '';
$UPDATE_TRANS = '';
$count_string = 'INV';
$count = 0;

foreach($result as $row){
    
    $count++;
    $ID = $row['ID'];
    $serial = sprintf("%05d", $ID);
    $serial = $count_string . $serial;
    $GROUP_ID = $row['GROUP_ID'];
    $IMG = $row['IMG'];
    $CATEGORY_TH = $row['CATEGORY_TH'];
    $SIZE = $row['SIZE'];
    $QTY = $row['QTY'];
    $TOTAL_PRICE = $row['PRICE'];
    $UID = $_SESSION['gid'];
    $EMAIL = $_SESSION['emp_email'];
    $DEPARTMENT = $_SESSION['department'];

    /* ผลลัพธ์ stock ของ tb_master */
    $stmt_master = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt_master->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
    $result_stock_master = $stmt_master->fetch(PDO::FETCH_OBJ);
    $CAL_result_stock_master = $result_stock_master->C_STOCK;

    $CAL_STOCK_MASTER = $CAL_result_stock_master - $QTY;
    /* จบ tb_master */

    /* ผลลัพธ์ stock ของ tb_group_master */
    $stmt_group_master = $connect->prepare("SELECT C_STOCK FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
    $stmt_group_master->execute(array(":id" => $GROUP_ID));
    $result_stock_group_master = $stmt_group_master->fetch(PDO::FETCH_OBJ);
    $C_STOCK = $result_stock_group_master->C_STOCK;

    $TOTAL_GROUP_MASTER_STOCK = $C_STOCK - $QTY;
    /* จบ tb_group_master */

    /* เช็ค period รับของ */
    $today = date('Y-m-d');
	$intHoliday = 0;

    $cutoff_period_1 = date("Y-m-12", strtotime($today));

    $cutoff_period_2 = date("Y-m-t", strtotime($today));
    $cutoff_period_2 = date("Y-m-d", strtotime($cutoff_period_2. ' - 4 days'));

    if($today <= $cutoff_period_1) {

        /* รับของที่ไม่ตรงกับวันหยุด*/
        $period_1 = date("Y-m-16", strtotime($today));
		$last_month = date("Y-m-t", strtotime($today));

		while(strtotime($period_1) <= strtotime($last_month)) {

			$stmt = $connect->prepare("SELECT * FROM tb_production_calendar WHERE CALENDAR_DATE = :CALENDAR_DATE AND STATUS_CHG = 0");
			$stmt->execute(array("CALENDAR_DATE" => $period_1));
			$result = $stmt->fetch(PDO::FETCH_OBJ);

			if(!$result)
			{
				return false;
			}
			else
			{
				$HOLIDAY = $result->HOLIDAY_FLAG;
				if($HOLIDAY == 0) {
					$receive = $period_1;
					break;
				} else {
					// return true;
				}
			}

			$period_1 = date ("Y-m-d", strtotime("+1 day", strtotime($period_1)));
		}
    } elseif($today <= $cutoff_period_2) {
        $period_2 = date("Y-m-t", strtotime($today));
        $period_2_receive = date("Y-m-d", strtotime($period_2. '+ 1 days'));

        $next_month = date("Y-m-d", strtotime($period_2_receive. '+ 15 days'));

        while(strtotime($period_2_receive) <= strtotime($next_month)) {
            
            $Database = new Database();
			$connect = $Database->connect();

			$stmt = $connect->prepare("SELECT * FROM tb_production_calendar WHERE CALENDAR_DATE = :CALENDAR_DATE AND STATUS_CHG = 0");
			$stmt->execute(array("CALENDAR_DATE" => $period_2_receive));
			$result = $stmt->fetch(PDO::FETCH_OBJ);

			if(!$result)
			{
				return false;
			}
			else
			{
				$HOLIDAY = $result->HOLIDAY_FLAG;
				if($HOLIDAY == 0) {
					$receive = $period_2_receive;
					break;
				} else {
					// return true;
				}
			}

			$period_2_receive = date ("Y-m-d", strtotime("+1 day", strtotime($period_2_receive)));
        
        }
    } else {
        $period_between = date("Y-m-t", strtotime($today));
        $next_month = date("Y-m-d", strtotime($period_between. '+ 16 days'));
        $last_month = date("Y-m-t", strtotime($next_month));

        while(strtotime($next_month) <= strtotime($last_month)) {
            
            $Database = new Database();
			$connect = $Database->connect();

			$stmt = $connect->prepare("SELECT * FROM tb_production_calendar WHERE CALENDAR_DATE = :CALENDAR_DATE AND STATUS_CHG = 0");
			$stmt->execute(array("CALENDAR_DATE" => $next_month));
			$result = $stmt->fetch(PDO::FETCH_OBJ);

			if(!$result)
			{
				return false;
			}
			else
			{
				$HOLIDAY = $result->HOLIDAY_FLAG;
				if($HOLIDAY == 0) {
					$receive = $next_month;
					break;
				} else {
					// return true;
				}
			}

			$next_month = date ("Y-m-d", strtotime("+1 day", strtotime($next_month)));
        
        }
    }
    

    $sql = "INSERT INTO tb_products (CART_TRANS,GROUP_ID,IMG,CATEGORY_TH,SIZE,QTY,TOTAL_PRICE, EMAIL, DEPARTMENT, ORDER_BY, ORDER_DATE, STATUS_ID, RECEIVE_DATE, STATUS_CHG)
            VALUES (:CART_TRANS,:id,:IMG,:CATEGORY_TH,:SIZE,:QTY,:TOTAL_PRICE, :EMAIL, :DEPARTMENT,:ORDER_BY,'".date("Y-m-d H:i:s")."', 0, :RECEIVE_DATE, 0)";
    $stmt = $connect->prepare($sql);
    $ADD_TRANS = $stmt->execute(array(":CART_TRANS" => $serial,":id" => $GROUP_ID,":IMG" => $IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":TOTAL_PRICE" => $TOTAL_PRICE,":EMAIL" => $EMAIL, "DEPARTMENT" => $DEPARTMENT,":ORDER_BY" => $UID, "RECEIVE_DATE" => $receive));

    $sql_update = "UPDATE tb_order_trans SET STATUS_ID = 1,STATUS_CHG = 1 WHERE ID = :ID_TRANS";
    $stmt_update = $connect->prepare($sql_update);
    $UPDATE_TRANS = $stmt_update->execute(array(":ID_TRANS" => $ID));

    /* Update stock master */
    $sql_update_stock_master = "UPDATE tb_master SET C_STOCK = :C_STOCK WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'";
    $stmt_update_stock_master = $connect->prepare($sql_update_stock_master);
    $UPDATE_STOCK_MASTER = $stmt_update_stock_master->execute(array("C_STOCK" => $CAL_STOCK_MASTER, ":id" => $GROUP_ID, ":SIZE" => $SIZE));

    /* Update stock group master */
    $sql_update_stock_group_master = "UPDATE tb_group_master SET C_STOCK = :C_STOCK WHERE GROUP_ID = :id AND STATUS_CHG = '0'";
    $stmt_update_stock_group_master = $connect->prepare($sql_update_stock_group_master);
    $UPDATE_STOCK_GROUP_MASTER = $stmt_update_stock_group_master->execute(array("C_STOCK" => $TOTAL_GROUP_MASTER_STOCK, ":id" => $GROUP_ID));


}

if($ADD_TRANS && $UPDATE_TRANS && $UPDATE_STOCK_MASTER && $UPDATE_STOCK_GROUP_MASTER) {
    http_response_code(200);
    echo json_encode(array('status' => true, 'message' => 'สั่งซื้อสินค้าสำเร็จ..'));
} else {
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถสั่งซื้อสินค้าได้!'));
}




?>
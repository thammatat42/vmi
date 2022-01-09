<?php
require_once 'service/connect.php';


    $today = date('Y-m-d');
	$intHoliday = 0;

    $cutoff_period_1 = date("Y-m-13", strtotime($today));

    $cutoff_period_2 = date("Y-m-t", strtotime($today));
    $cutoff_period_2 = date("Y-m-d", strtotime($cutoff_period_2. ' - 3 days'));

    // echo "<hr>";
	// echo "<br>วันนี้: $today";
	// echo "<br>cutoff period 1: $cutoff_period_1";
	// echo "<br>cutoff period 2: $cutoff_period_2";


    if($today <= $cutoff_period_1) {
        // echo "<hr>";
        // echo "<br>เข้าเงื่อนไขที่ 1";

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
        // echo "<hr>";
        // echo "<br>เข้าเงื่อนไขที่ 2";
        /* รับของวันที่ สิ้นเดือน */
        $period_2 = date("Y-m-t", strtotime($today));
        $next_month = date("Y-m-d", strtotime($period_2. '+ 15 days'));

        while(strtotime($period_2) <= strtotime($next_month)) {
            
            $Database = new Database();
			$connect = $Database->connect();

			$stmt = $connect->prepare("SELECT * FROM tb_production_calendar WHERE CALENDAR_DATE = :CALENDAR_DATE AND STATUS_CHG = 0");
			$stmt->execute(array("CALENDAR_DATE" => $period_2));
			$result = $stmt->fetch(PDO::FETCH_OBJ);

			if(!$result)
			{
				return false;
			}
			else
			{
				$HOLIDAY = $result->HOLIDAY_FLAG;
				if($HOLIDAY == 0) {
					$receive = $period_2;
					break;
				} else {
					// return true;
				}
			}

			$period_2 = date ("Y-m-d", strtotime("+1 day", strtotime($period_2)));
        
        }
    } else {
        // echo "<hr>";
        // echo "<br>เข้าเงื่อนไขที่ 3";
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
    
    // echo "<hr>";
    // echo "<br>วันรับของ: $receive";
?>
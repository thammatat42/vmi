<?php
	
    class Database {
        private $host = "localhost";
        private $dbname = "vmi";
        // private $username = "baadmin";
        // private $password = "P@ssw0rd1234%";
        private $username = "root";
        private $password = "";
        private $conn = null;
    
        public function connect() {
            try{
                $this->conn = new PDO('mysql:host='.$this->host.'; 
                                    dbname='.$this->dbname.'; 
                                    charset=utf8', 
                                    $this->username, 
                                    $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo "เชื่อมต่อสำเร็จ";
            }catch(PDOException $exception){
                echo "ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $exception->getMessage();
                exit();
            }
            return $this->conn;
        }
    }

	function CheckPublicHoliday($strChkDate)
	{
        $Database = new Database();
        $connect = $Database->connect();

		$stmt = $connect->prepare("SELECT * FROM tb_calendar WHERE P_HOLIDAY = :P_HOLIDAY AND STATUS_CHG = 0");
        $stmt->execute(array("P_HOLIDAY" => $strChkDate));
        $result = $stmt->fetch(PDO::FETCH_OBJ);

		if(!$result)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	

	$PRESENT_MONTH = date("Y-01-01");
	$LAST_MONTH = date("Y-12-31");
	

	$strStartDate = $PRESENT_MONTH;
	$strEndDate = $LAST_MONTH;
	
	$intWorkDay = 0;
	$intHoliday = 0;
	$intPublicHoliday = 0;
	$intTotalDay = ((strtotime($strEndDate) - strtotime($strStartDate))/  ( 60 * 60 * 24 )) + 1; 

	while (strtotime($strStartDate) <= strtotime($strEndDate)) {
		
		$DayOfWeek = date("w", strtotime($strStartDate));
		if($DayOfWeek == 0 or $DayOfWeek ==6)  // 0 = Sunday, 6 = Saturday;
		{
			$intHoliday++;
			echo "$strStartDate = <font color=red>Holiday</font><br>";
		}
		elseif(CheckPublicHoliday($strStartDate))
		{
			$intPublicHoliday++;
			echo "$strStartDate = <font color=orange>Public Holiday</font><br>";
		}
		else
		{
			$intWorkDay++;
			echo "$strStartDate = <b>Work Day</b><br>";
		}

		$strStartDate = date ("Y-m-d", strtotime("+1 day", strtotime($strStartDate)));
	}

	echo "<hr>";
	echo "<br>Total Day = $intTotalDay";
	echo "<br>Work Day = $intWorkDay";
	echo "<br>Holiday = $intHoliday";
	echo "<br>Public Holiday = $intPublicHoliday";
	echo "<br>All Holiday = ".($intHoliday+$intPublicHoliday);


?>
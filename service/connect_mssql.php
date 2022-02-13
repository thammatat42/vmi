<?php 
/**
 **** Develop by Business Application Dept. ****
 * Connect Database PHP PDO
 */
session_start();
error_reporting(E_ALL); 

/* On production */
// error_reporting(0);

date_default_timezone_set('Asia/Bangkok');

/** Class Database สำหรับติดต่อฐานข้อมูล */
class Database {
    /**
     * กำหนดตัวแปรแบบ private
     * Method Connect ใช้สำหรับการเชื่อมต่อ Database
     *
     * @var string|null
     * @return PDO
     */
    // private $host = "54.179.74.126";
    private $host = "THSTTBDWH01";
    private $dbname = "SMART_PLANING";
    // private $username = "baadmin";
    // private $password = "P@ssw0rd1234%";
    private $username = "baadmin";
    private $password = "ba@Min";
    private $conn = null;
    public function connect() {
        try{
            /** PHP PDO */
            $this->conn = new PDO('sqlsrv:Server='.$this->host.'; 
                                Database='.$this->dbname.';', 
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

/**
 * ประกาศ Instance ของ Class Database
 */
$Database = new Database();
$conn = $Database->connect();


?>
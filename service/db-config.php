<?php

    class DBController {

        private $hostname       =       "54.179.74.126";

        private $username       =       "baadmin";

        private $password       =       "P@ssw0rd1234%";

        private $db             =       "vmi";

        // Creating connection

        public function connect() {

            $conn               =       new mysqli($this->hostname, $this->username, $this->password, $this->db)or die("Database connection error." . $conn->connect_error);

            return $conn;           
        }

        // Closing connection

        public function close($conn) {

            $conn->close();
        }

    }

    $db = new DBController();

    $conn = $db->connect();
?>
<?php
$serverName = "THSTTBDWH01";
$connectionInfo = array( "Database"=>"SMART_PLANING", "UID"=>"baadmin", "PWD"=>"ba@Min");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     // echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
<?php
/* เปิด session */
SESSION_START();

require_once "../function/LDAP.func";
require_once "../function/MSSQL.func";
require_once '../connect_sqlsrv.php';

/* ปิด warning */
ini_set('display_errors','Off');

/* Input */
$username = '5022210064';
$password = '1234';



if (LDAP_LOGIN($username,$password,$conn,'RCA_WIP')) {

    $gid = $_SESSION[gid];
    $emp_name = $_SESSION[emp_name];
    $emp_email = $_SESSION[emp_email];
    $emp_dept = $_SESSION[emp_dept];
    $emp_company = $_SESSION[emp_company];
    $AD_LOGIN = $_SESSION[getdate()];



    http_response_code(200);
    echo json_encode(array('status' => true, 'message' => 'Login Success!'));
} else {
    http_response_code(401);
    echo json_encode(array('status' => false, 'message' => 'No authorized on system!'));
}

// print_r ($data);

?>
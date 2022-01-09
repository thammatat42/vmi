<?php
/**
 */
header('Content-Type: application/json');
require_once '../connect.php';
require_once "../function/LDAP.func";
require_once "../function/MSSQL.func";
// require_once '../connect_sqlsrv.php';
ini_set('display_errors','Off');


$username = $_POST['username'];
$password = $_POST['password'];
$remember_me = $_POST['remember'];



if($_SERVER['REQUEST_METHOD'] === "POST"){
    if (LDAP_LOGIN($username, $password,$conn, $connect,'RCA_WIP')) {

        $gid = $_SESSION[gid];
        $emp_name = $_SESSION[emp_name];
        $Array_name = explode(" ",$emp_name);
        $emp_email = $_SESSION[emp_email];
        $emp_dept = $_SESSION[emp_dept];
        $emp_company = $_SESSION[emp_company];
        $AD_FNAME = $Array_name[0];
        $AD_LNAME = $Array_name[1];
        $AD_LOGIN = $_SESSION[status];
        $AD_STATUS = $_SESSION[STATUS_TYPE];
        $AD_IMG = $_SESSION[img_url];

        

        $_SESSION['AD_ID'] = $gid;
        $_SESSION['AD_FIRSTNAME'] = $AD_FNAME;
        $_SESSION['AD_LASTNAME'] =  $AD_LNAME;
        $_SESSION['AD_USERNAME'] = $gid;
        $_SESSION['AD_IMAGE'] = $AD_IMG;
        $_SESSION['AD_STATUS'] = $AD_STATUS;
        $_SESSION['AD_LOGIN'] = $AD_LOGIN;
    
        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'Login Success!'));
    }
    
} else {
    http_response_code(405);
    echo json_encode(array('status' => false, 'message' => 'Method Not Allowed!'));
    header("Location: ../../pages/includes/error-405.php");
    exit();
}
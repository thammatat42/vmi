<?php 
    require_once '../../service/connect.php' ; 
    if( !isset($_SESSION['AD_ID'] ) ){
        header('Location: ../../login.php'); 
    } 
?>
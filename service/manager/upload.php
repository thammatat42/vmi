<?php
header('Content-Type: application/json');
require_once '../connect.php';

$t = microtime(true);
$micro = sprintf("%06d",($t - floor($t)) * 1000000);
$datetime = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
$CHG_FILES = explode(".",$_FILES["customfile"]["name"]);
$CHG_FILES = $datetime->format("Ymdu").'.'.$CHG_FILES[1];
$target_dir = "../../assets/upload/";
$target_file = $target_dir . basename($CHG_FILES);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);



// Check if image file is a actual image or fake image
if (isset($_POST["upload"])) {

    if ($target_file == "upload/") {
        $msg = "cannot be empty";
        $uploadOk = 0;
    } 
    // Check if file already existselse 
    if (file_exists($target_file)) {
        $msg = "Sorry, file already exists.";
        $uploadOk = 0;
    } 
    // Check file sizeelse 
    if ($_FILES["customfile"]["size"] > 5000000) {
        $msg = "Sorry, your file is too large.";
        $uploadOk = 0;
    } 
    // Check if $uploadOk is set to 0 by an errorelse 
    if ($uploadOk == 0) {
        $msg = "Sorry, your file was not uploaded.";

    /* if everything is ok, try to upload file */
    } else {
        print_r($target_file);
        if (move_uploaded_file($_FILES["customfile"]["tmp_name"], $target_file)) {
            $msg = "The file " . basename($target_file) . " has been uploaded.";
        }
    }
}
?>
<?php
function LDAP_LOGIN($username,$password,$conn,$connect,$pages)
{
	set_time_limit (5);
    ob_start();

   
    $Username = urlencode($username);
    $Password = urlencode($password);
    
    $url = "http://43.72.228.147:3000/api/login/?gid=".$Username."&password=".$Password; 

    $str_token = '956a9947ccceae2472dcc7a67eb4669819267ae5';

    $context = stream_context_create(array(
        'http' => array(
            'header'  => "Authorization: Token " .$str_token,
            'timeout' => 10 //1200 Seconds is 20 Minutes
        )
    ));
    
    $data = file_get_contents($url, false, $context);
    
    
    if(!$data)
    {
        
        echo "<script>alert('Server authenticateUser not response in 5 sec')</script>";
        return false;
    }

    $array = json_decode($data, true);

    if(strlen($username) == 10){
        $username = substr($username,2);
    } else {
        $username = $username;
    }

    $stmt = $connect->prepare("SELECT * FROM tb_user WHERE USERNAME = :username AND STATUS_CHG = '0' and cast(CREATE_DATE as Date) < EXPIRED_DATE");
    $stmt->execute(array(":username" => $username));
    //$result = $stmt->fetch(PDO::FETCH_OBJ);

    if($stmt->fetch(PDO::FETCH_OBJ)){
        if($array[RESULT] == "PASS") {

            $user_id = substr($username,2,10);
            $array['IMAGE'] = "http://43.72.228.147:8000/attend/img_opt/$user_id.jpg";


            $_SESSION[gid] = $array['GID'];
            $_SESSION[emp_name] = $array['NAME'];
            $_SESSION[emp_email] =  $array['EMAIL'];
            $_SESSION[emp_dept]  =  $array['DEPT'];
            $_SESSION[emp_company] =  $array['COMPANY']; 
            $AD_LOGIN2 = 'Admin';
            $_SESSION[STATUS_TYPE] = $AD_LOGIN2;

            $gid = $array['GID'];
            $emp_name = $array['NAME'];
            $emp_email = $array['EMAIL'];
            $emp_dept = $array['DEPT'];
            $emp_company = $array['COMPANY'];

            $count = $connect->exec("UPDATE tb_user SET UPDATE_DATE = '".date("Y-m-d H:i:s")."' WHERE UID = $gid");
            $_SESSION[status] = $result->UPDATE_DATE;
            $_SESSION[img_url] = $result->IMG;
            return true;
        } else {
            if( !empty($result) && md5($password) == $result->PASSWORD ){

                $_SESSION[gid] = $result->UID;
                $emp_name = $result->FNAME.' '.$result->LNAME;
                $_SESSION[emp_name] = $emp_name;
                $count = $connect->exec("UPDATE tb_user SET UPDATE_DATE = '".date("Y-m-d H:i:s")."' WHERE UID = $result->UID");
                $_SESSION[status] = $result->UPDATE_DATE;
                $_SESSION[STATUS_TYPE] = $result->STATUS_TYPE;
                $_SESSION[img_url] = $result->IMG;
                return true;
            } else {
                return false;
            }
        }
    } else {
        //หมดอายุให้เท่ากับ 3
        $_SESSION[expired] = 3;
    }


    
}

?>
<?php
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

date_default_timezone_set("Asia/Taipei"); 
$time = date('Y-m-d G:i') ;

$email = $_COOKIE['ck_account'] ;
$password = $_COOKIE['ck_password'] ;
$email_account = getEmailAccount($email) ;

$oldName = string_legalize($_GET['old']) ;
$newName = string_legalize($_GET['now']) ;

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$db = 'user_files' ;
$set = "name='$newName' , upload_time='$time'" ;
$where = "owner='$email_account' and name='$oldName'" ;
$result = $conn->update_data($db,$set,$where) ;
$conn = mySQL_connection::stop_connected();

$folder = 'user_files/'.$email_account.'/' ;
rename($folder.$oldName,$folder.$newName) ;

echo $result ;

?> 
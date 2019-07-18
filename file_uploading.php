<?php 
include('get_mySQL.php') ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

$account = $_COOKIE['ck_account'] ;
$password = $_COOKIE['ck_password'] ;
$email_account = getEmailAccount($account) ;

date_default_timezone_set("Asia/Taipei"); 

$target_dir = "user_files/" . $email_account . "/" ;

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db);
$db = 'user_files' ;  
$link = 'file_downloading.php?id=user_files/' . $email_account . '/' ;

$count = count($_FILES["upload_field"]['name']) ;


    for ( $i = 0 ; $i < $count ; $i++ ) {

        $filename = string_legalize(basename($_FILES["upload_field"]['name'][$i])) ;
        $filesize = $_FILES['upload_field']['size'][$i] ;
        $target_file = $target_dir . $filename ;
        $downloadLink = $link . $filename ;
        $time = date('Y-n-d G:i') ;

        $uploadOk = 1 ;
        $uploaded = false ;

        if ( file_exists($target_file) ) {
            unlink($target_file) ;
            $uploaded = true ;
        } 

        if ( $_FILES['upload_field']['size'][$i] > 15728640 ) $uploadOk = 0 ;  //max size = 15MB

        if ( $uploadOk == 0 ) {  }
        else move_uploaded_file($_FILES['upload_field']['tmp_name'][$i],$target_file) ; 

        if ( $uploaded == true ) {
            $set_value = "size='$filesize', upload_time='$time'" ;
            $where = "owner='$email_account' and name='$filename'" ;
            $result = $conn->update_data($db,$set_value,$where) ;
        }else if ( $uploadOk == 1 ){
            $fields = "(owner,name,size,download_link,upload_time)" ;
            $values = "('$email_account','$filename','$filesize','$downloadLink','$time')" ;
            $result = $conn->insert_to($db,$fields,$values) ;
        }

    }

$conn = mySQL_connection::stop_connected();

Header("Location: myFile.php") ;

?>
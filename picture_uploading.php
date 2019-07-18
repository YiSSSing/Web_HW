<?php
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

$account = $_COOKIE['ck_account'] ;
$password = $_COOKIE['ck_password'] ;
$email_account = getEmailAccount($account) ;

if ( isset($_COOKIE['change_icon']) ) $flag = true ;
setcookie('change_icon') ;

$target_dir = "user_pictures/" . $email_account . "/" ;

//here user change user icon
if ( isset($flag) )  {

$target_file = $target_dir . basename($_FILES["upload_field"]['name']) ;
$uploadOk = 1 ;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)) ;

if ( isset($_POST['submit'])) {
    $check = getimagesize($_FILES['upload_field']['tmp_name']) ;
    if ( $check !== false ) $uploadOk = 1 ; //file is an image
    else {
        setcookie("picUpload","請上傳正確的圖檔") ;
        $uploadOk = 0 ;
    }
}

if ( file_exists($target_file) ) {
    setcookie("picUpload","圖片已更新") ;
    $uploadOk = 0 ;
    $uploaded = "123" ;
} 

if ( $_FILES['upload_field']['size'] > 3145728 ) {
    setcookie("picUpload","檔案過大(超過3MB)") ;
    $uploadOk = 0 ;
}

if ( $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "png" ) {
    setcookie("picUpload","只接受jpg,jpeg,gif,png格式的圖片") ;
    $uploadOk = 0 ;
}

if ( $uploadOk == 0 ) { }
else {
    if ( move_uploaded_file($_FILES['upload_field']['tmp_name'],$target_file) ) {
        setcookie("picUpload","success") ;
    }else setcookie("picUpload","fail") ;
}

//here user upload picture
}else {

    $count = count($_FILES['upload_field']['name']) ;

    for ( $i = 0 ; $i < $count ; $i++ ) {

        $target_file = $target_dir . basename($_FILES["upload_field"]['name'][$i]) ;
        $uploadOk = 1 ;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)) ;

        if ( isset($_POST['submit'])) {
            $check = getimagesize($_FILES['upload_field']['tmp_name'][$i]) ;
            if ( $check !== false ) $uploadOk = 1 ; //file is an image
            else  $uploadOk = 0 ;
        }

        if ( file_exists($target_file) ) $uploadOk = 0 ;


        if ( $_FILES['upload_field']['size'][$i] > 3145728 ) $uploadOk = 0 ;


        if ( $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "png" ) $uploadOk = 0 ;


        if ( $uploadOk == 0 ) { }
        else move_uploaded_file($_FILES['upload_field']['tmp_name'][$i],$target_file) ;


    }

}


if ( isset($flag) && ($uploadOk == 1 || isset($uploaded)) ) {
    $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db);
    $db = "user_account" ;
    $set_value = "picture='user_pictures/" . $email_account . "/" . $_FILES['upload_field']['name'] . "'" ;
    $where = "email='$account' and password='$password'" ;
    $result = $conn->update_data($db,$set_value,$where) ;
    if ( $result == false || $result == null || $result <= 0 ) setcookie("picUpload","fail") ; 
    $conn = mySQL_connection::stop_connected();
}
if ( isset($flag) ) header("Location: user_edit_information.php") ;
else header("Location: myPicture.php") ;

?>


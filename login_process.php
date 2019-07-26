

<?php
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

if ( isset($_COOKIE["first_name"])) {
    $fname = $_COOKIE["first_name"]; 
    $lname = $_COOKIE["last_name"] ;
    $gender = $_COOKIE["gender"] ;
    $email = $_COOKIE["email"] ;
    $password = $_COOKIE["password"] ;
    $comment = ( isset($_COOKIE["comment"]) ? $_COOKIE["comment"] : "none" ) ;
    $comment = string_legalize($comment) ;
    $nickname = $_COOKIE["first_name"] . " " . $_COOKIE["last_name"] ;
    $pic = "none" ;
}else {
    echo "Connecting time up... Please login again."."<br>" ;
    die() ;
}
 
$eac = getEmailAccount($email) ;
//create user information
$db = "user_account" ;
$fields = "(email,eaccount,password,first_name,last_name,gender,comment,nickname,picture,friends)" ;
$values = "('$email','$eac','$password','$fname','$lname','$gender','$comment','$nickname','$pic','none')" ;
$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$result = $conn->insert_to($db,$fields,$values) ;
$conn = mySQL_connection::stop_connected();

$picDir = getEmailAccount($email) ;
mkdir("user_pictures/".$picDir) ;
mkdir('user_files/'.$picDir) ;

if ( ! $result ) echo "Connection ERROR...<br>" ;
else setcookie("signUpSuccess","success") ;

echo $fname."<br>" ;
echo $lname."<br>" ;
echo $gender."<br>" ;
echo $email."<br>";
echo $password."<br>";
echo $comment."<br>" ;
echo $nickname."<br>" ;
echo $pic."<br>" ;


header("Location: sign_in.php") ;

?>
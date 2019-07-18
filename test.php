<?php
date_default_timezone_set("Asia/Taipei"); 
$time = date('Y-n-d G:i') ;
?>

<?php 
include('get_mySQL.php') ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;
/*
$a = array() ;
for ( $i = 0 ; $i < 100 ; $i++ ) {
    $a[$i] = $i * $i ;
} 
echo "<pre>" . print_r($a,true) . "</pre>" ;
*/ 
/*
$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$result = $conn->get_select('user_account','last_login',"eaccount='Kaguya'") ;
$conn = mySQL_connection::stop_connected() ;
*/

$string = "what&a&sexy&pussy" ;
echo substr($string,0,-5) . "<br>" ;
echo substr($string,0,strpos($string,"sexy"))."<br>" ;
echo substr($string,strpos($string,"sexy")+4) ;


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Files</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet'>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet"/>

  <style>
    
  </style>

</head>

<div id = "test"></div>
<div id = "test2"></div>
<input type="text" onkeydown="enterON()">

<html>
<head>
<title>使用enter鍵替代某個按鈕</title>
<script language="JavaScript">
  /*
function keyLogin(){
if (event.keyCode==13) //enter的鍵值為13
document.getElementById("input1").click(); //觸動按鈕的點擊
}
*/
function enterON() {
  if ( event.keyCode == 13 ) document.getElementById("input1").click(); 
}
</script>
</head>
<body onkeydown="keyLogin();">
<input id="input1" value="按鈕" type="button" onclick="alert('按鈕已按')">
</body>
</body>
</html>


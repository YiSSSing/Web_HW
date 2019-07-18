<?php 
include('get_mySQL.php') ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;


header("Content-type:text/html;charset=utf-8"); 

$file_name = $_GET['id'] ;
if ( empty($file_name) ) die() ; 
echo $_GET['id'] ;
//solve the problem with chinese file name 
$file_name=iconv("utf-8","gb2312",$file_name); 
$file_path = $file_name; 
echo $file_path ;
//if file not exist
if(!file_exists($file_path)){ 
echo "Connecting ERROR : no such file exist"; 
die() ;
} 

$fp=fopen($file_path,"r"); 
$file_size=filesize($file_path); 

//headers for downloading
Header("Content-type: application/octet-stream"); 
Header("Accept-Ranges: bytes"); 
Header("Accept-Length:".$file_size); 
Header("Content-Disposition: attachment; filename=".$file_name); 
$buffer=1024; 
$file_count=0; 

//return datas to broswer
while(!feof($fp) && $file_count<$file_size){ 
$file_con=fread($fp,$buffer); 
$file_count =$buffer; 
echo $file_con; 
} 
fclose($fp); 

?>

<script>
    window.location.replace('myFile.php') ;
</script>
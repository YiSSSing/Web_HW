<?php 
if ( !isset($_COOKIE['ck_account'])) header("Location: sign_in.php") ;

$user_nickname = $_COOKIE['nick'] ;
setcookie("nick") ;

include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;


/*
$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$fields = "*" ;
$db = "user_account" ;
$where = "email = '$account' and password = '$password'" ;
$result = $conn->get_select($db,$fields,$where) ;
$conn = mySQL_connection::stop_connected();
*/

//reference : https://www.w3schools.com/php/php_file_upload.asp
//reference : http://www.webtech.tw/info.php?tid=24
//reference : https://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <title>Upload</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<body>

    <script type="text/javascript">
        function logoutOnClick() {
            document.cookie = "loging_out = true" ;
            window.location.replace('sign_in.php') ;
        }
    </script>

    <script type="text/javascript">
      function cancelOnClick() {
          window.location.replace('user_edit_information.php') ;
      }
    </script>

    <script type="text/javascript">
      function uploadOnClick() {
          var x = document.getElementById("upload_field").input ;
          var form = document.forms['picForm'] ;
          if ( !document.getElementById("upload_field").value ) alert("請選取一張照片") ; 
          else {
            document.cookie = "change_icon=yes" ;
            form.submit()
          }
      }
    </script>

    <script type="text/javascript">
        function readURL(input) {
            if ( input.files && input.files[0] ) {
                var imageTagId = input.getAttribute("targetID") ;
                var reader = new FileReader() ;
                reader.onload = function(e) {
                    var img = document.getElementById(imageTagId) ;
                    img.setAttribute("src",e.target.result) ;
                }
                reader.readAsDataURL(input.files[0]) ;
            }
        }
    </script>

    <div class="container-fluid">
        <div class="row" style="height:80px; background-color:royalblue">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h1 style="color:cornsilk"><dl><dt><?php echo($user_nickname);?></dt></dl></h1>
            </div>
            <div class="col-sm-1">
                <input type="button" class="btn" id="log_out" name="log_out" value="登出" style="background-color:royalblue; color:white; font-size:32px; height:80px; text-align:center;" onclick="logoutOnClick()">
            </div>
        </div>
        <form class="form-horizontal" id="picForm" name="picForm" action="picture_uploading.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-8" >
                <input type="file" id="upload_field" name="upload_field" onchange="readURL(this)" targetID="input_pic" accept="image/gif,image/jpeg,image/png,image/jpg" style="font-size:32px; width:max-content; margin:0px auto;" >
            </div>
            <div class="col-sm-2"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <img id="input_pic" src="Nobody.jpg" class="img-responsive" width="486" height="552" style="margin:0px auto;">
            </div>
        </div>
        <div class="col-sm-12" style="margin-top:50px;"></div>
        <div class="col-sm-6" style="text-align:right;">
            <input type="button" class="btn" id="upload_sure" name="upload_sure" style="width:180px; height:60px; font-size:28px; font-weight:bold;" value="確認上傳" onclick="uploadOnClick()">
        </div>
        <div class="col-sm-6">
            <input type="button" class="btn" id="upload_cancel" name="upload_cancel" style="width:180px; height:60px; font-size:28px; font-weight:bold;" value="取消上傳" onclick="cancelOnClick()">
        </div>
        </form>
    </div>


</body>
<?php session_start() ?>

<?php
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

$infoUpdateError = " " ;
$checkPoint = 0 ;

if ( isset($_COOKIE['picUpload']) ) {
  if ( $_COOKIE['picUpload'] == "fail" ) $infoUpdateError = "照片上傳錯誤" ;
  else if ($_COOKIE['picUpload'] == "success" ) $infoUpdateError = "照片上傳成功" ;
  else $infoUpdateError = $_COOKIE['picUpload'] ;
  setcookie('picUpload') ;
}

if ( isset($_COOKIE['user_upload'])) {
  $account = $_COOKIE['user_upload'] ;
  $new_password = $_COOKIE['new_password'] ;
  $new_nickname = $_COOKIE['new_nickname'] ;
  $new_fname = $_COOKIE['new_fname'] ;
  $new_lname = $_COOKIE['new_lname'] ;
  $new_gender = $_COOKIE['new_gender'] ;
  $new_selfintro = $_COOKIE['new_selfintro'] ;
  $password = $_COOKIE['old_password'] ;
  setcookie('new_password') ;
  setcookie('new_nickname') ;
  setcookie('new_fname') ;
  setcookie('new_lname') ;
  setcookie('new_gender') ;
  setcookie('new_selfintro') ;
  setcookie('user_upload') ;
  setcookie('old_password') ;

  if ( !isStringLegal($new_nickname) || !isStringLegal($new_fname) || !isStringLegal($new_lname) ) {
    $infoUpdateError = "輸入非法字元" ;
  }else $checkPoint += 1 ;

  if ( strlen($new_password)>=20 || strlen($new_password)<8 ) {
    $infoUpdateError = "密碼長度錯誤" ;
    $new_password = null ;
  }else $checkPoint += 1 ;

  if ( $new_gender != "male" && $new_gender != "female" && $new_gender != "none") {
    $infoUpdateError = "性別只能輸入male,female或none" ;
  }else $checkPoint += 1 ;

  if ( empty($new_fname) || empty($new_gender) || empty($new_lname) || empty($new_nickname) ) {
    $infoUpdateError = "還有空格尚未輸入" ;
  }else $checkPoint += 1 ;

  if ( empty($new_selfintro) ) $new_selfintro = "none" ;
  $new_selfintro = string_legalize($new_selfintro) ;

  if ( $checkPoint >= 4 ) {
  $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db);
  $db = "user_account" ;
  $set_value = "password='$new_password', nickname='$new_nickname', first_name='$new_fname', last_name='$new_lname', gender='$new_gender', comment='$new_selfintro'" ;
  $where = "email='$account' and password='$password'" ;
  $result = $conn->update_data($db,$set_value,$where) ;
  if ( $result == false || $result <= 0 ) $infoUpdateError = "連線錯誤" ;
  $conn = mySQL_connection::stop_connected();
  }
}

if ( isset($_SESSION['keep_name']) ) {
  $account = $_SESSION['keep_name'] ;
  $password = $_SESSION['keep_password'] ;
}else if ( isset($_COOKIE['ck_account']) ){
  $account = $_COOKIE['ck_account'] ;
  $password = $_COOKIE['ck_password'] ;
}else {
  echo "first" ;
  die() ;
  header("Location: sign_in.php") ;
  die() ;
}

if ( isset($new_password) ) {
  $password = $new_password ;
}

$user_picture = "Nobody.jpg" ;

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$fields = "*" ;
$db = "user_account" ;
$where = "email = '$account' and password = '$password'" ;
$result = $conn->get_select($db,$fields,$where) ;
$conn = mySQL_connection::stop_connected();


if ( !isset($result) || empty($result) ) {
  echo "second" ;
  die() ;
  header("Location: sign_in.php") ;
  die() ;
}

$user_nickname = $result[0]['nickname'] ;
$user_fname = $result[0]['first_name'] ;
$user_lname = $result[0]['last_name'] ;
$user_gender = $result[0]['gender'] ;
$user_selfintro = $result[0]['comment'] ;
if ( $result[0]['picture'] != "none" ) $user_picture = $result[0]['picture'] ;

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Information</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>


<body style="background-color:#EDEDED;">

  <script type="text/javascript">
    window.onbeforeunload() = function() {
      document.cookie = "user_upload=" ;
      document.cookie = "new_nickname=" ;
      document.cookie = "new_fname=" ;
      document.cookie = "new_lname=" ;
      document.cookie = "new_gender=" ;
      document.cookie = "new_selfintro=" ;
      document.cookie = "new_password=" ;
      document.cookie = "old_password=" ;
    }
  </script>

  <script type="text/javascript">
    function logoutOnClick() {
      document.cookie = "loging_out = true" ;
      window.location.replace('sign_in.php') ;
    }
  </script>

  <script type="text/javascript">
    function myButtonOnMouse(obj) {
      document.getElementById(obj).style.backgroundColor = "snow" ;
    }
  </script>

  <script type="text/javascript">
    function myButtonOutMouse(obj) {
      document.getElementById(obj).style.backgroundColor = "#EDEDED" ;
    }
  </script>

  <script type="text/javascript">
    function gobackOnClick() {
      window.location.href = 'message_board.php' ;
    }
  </script>

  <script type="text/javascript">
    function upload_pictureOnClick() {
      document.cookie = "ck_account=".concat(document.getElementById("edit_account").innerHTML) ;
      document.cookie = "ck_password=".concat(document.getElementById("edit_password").innerHTML) ;
      document.cookie = "nick=".concat(document.getElementById("edit_nickname").innerHTML) ;
      window.location.replace('user_upload_picture.php') ;
    }
  </script>


  <script type="text/javascript">
    function myButtonDownMouse(obj) {
      document.getElementById(obj).style.backgroundColor = "lightgray" ;
    }
  </script>

  <script type="text/javascript">
    function myButtonUpMouse(obj) {
      document.getElementById(obj).style.backgroundColor = "#EDEDED" ;
    }
  </script>

  <script type="text/javascript">
    function editInformationOnClick(x) {
        document.cookie = "old_password=".concat(document.getElementById("edit_password").innerHTML) ;
        document.getElementById("edit_nickname").contentEditable = "true" ;
        document.getElementById("edit_fname").contentEditable = "true" ;
        document.getElementById("edit_lname").contentEditable = "true" ;
        document.getElementById("edit_gender").contentEditable = "true" ;
        document.getElementById("edit_selfintro").contentEditable = "true" ;
        document.getElementById(x).style.display = "none" ;
        document.getElementById("finishInformation").style.display = "initial" ;
    }
  </script>

  <script type="text/javascript">
    function editPasswordOnClick(x) {
      document.cookie = "old_password=".concat(document.getElementById("edit_password").innerHTML) ;
      document.getElementById(x).style.display = "none" ;
      document.getElementById("finishPassword").style.display = "initial" ;
      document.getElementById("edit_password").contentEditable = "true" ;
    }
  </script>

  <script type="text/javascript">
    function finishOnClick() {
      document.cookie = "user_upload=".concat(document.getElementById("edit_account").innerHTML) ;
      document.cookie = "new_nickname=".concat(document.getElementById("edit_nickname").innerHTML) ;
      document.cookie = "new_fname=".concat(document.getElementById("edit_fname").innerHTML) ;
      document.cookie = "new_lname=".concat(document.getElementById("edit_lname").innerHTML) ;
      document.cookie = "new_gender=".concat(document.getElementById("edit_gender").innerHTML) ;
      document.cookie = "new_selfintro=".concat(document.getElementById("edit_selfintro").innerHTML) ;
      document.cookie = "new_password=".concat(document.getElementById("edit_password").innerHTML) ;
      window.location.reload() ;
    }
  </script>

  <script type="text/javascript">
    function myFileOnClick() {
      window.location.replace('myFile.php') ;
    }
  </script>

  <script type="text/javascript">
    function myPictureOnClick() {
      document.cookie ='nickname='.concat(document.getElementById("nname").innerHTML) ;
      document.cookie ='picture='.concat(document.getElementById("uimg").src) ;
      window.location.replace('myPicture.php') ;
    }
  </script>

  <script type="text/javascript">
    function myFriendOnClick() {
      window.location.replace('myFriend.php') ;
    }
  </script>
  

  <div class="container-fluid">
    <div class="row" style="height:80px; background-color:royalblue" >
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <h1 style="color:cornsilk" id="nname"><dl><dt><?php echo($user_nickname);?></dt></dl></h1>
      </div>
      <div class="col-sm-1">
        <input type="button" class="btn" id="log_out" name="log_out" value="登出" style="background-color:royalblue; color:white; font-size:32px; height:80px; text-align:center;" onclick="logoutOnClick()">
      </div>
    </div>
    <br>      
    <div class = "col-sm-3" style = "height:max-content; text-align:center;" id="user_interface" name="user_interface">
      <img src=<?php echo($user_picture);?> class="img-circle img-responsive" width="324" height="368" style="margin:0px auto;" id="uimg">
      <br>
      <div style="color:black; font-size:26px;" id="upload_picture" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="upload_pictureOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>上傳照片</dt></dl></div>
      <div style="color:black; font-size:26px;" id="goback" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="gobackOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>留言板</dt></dl></div>
      <div style="color:black; font-size:26px;" id="myFile" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFileOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的檔案</dt></dl></div>
      <div style="color:black; font-size:26px;" id="myPicture" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myPictureOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的照片</dt></dl></div>
      <div style="color:black; font-size:26px;" id="myFriend" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFriendOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>好友列表</dt></dl></div>
    </div>
    <div class = "col-sm-9" style="height:max-content" id="information_board" name="information_board">
      <div class = "row" ></div>
      <div class="col-sm-6" style="font-size:36px;"><dl><dt>一般資訊</dt></dl></div>
      <div class="col-sm-6" style="font-size:24px; color:red;"><?php echo($infoUpdateError) ; ?></div>
      <div class = "col-sm-12" style="background-color:gray; height:3px;"></div>
      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">Nickname :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_nickname" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($user_nickname)?></div>
      <div class="col-sm-12" style="background-color:gray; height:1px;"></div>

      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">First name :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_fname" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($user_fname)?></div>
      <div class="col-sm-12" style="background-color:gray; height:1px;"></div>

      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">Last name :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_lname" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($user_lname)?></div>
      <div class="col-sm-12" style="background-color:gray; height:1px;"></div>

      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">Gender :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_gender" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($user_gender)?></div>
      <div class="col-sm-12" style="background-color:gray; height:1px;"></div>

      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">Self-Intro :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_selfintro" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($user_selfintro)?></div>

      <div class="col-sm-12" style="margin-top:40px; text-align:right;">
        <input type="button" class="btn" id="editInformation" name="editInformation" style="margin-right:100px; height:40px; width:100px;" value="編輯" onclick="editInformationOnClick(this.id)"> 
        <input type="button" class="btn" id="finishInformation" name="finishInformation" style="margin-right:100px; height:40px; width:100px; display:none;" value="確認送出" onclick="finishOnClick()">
      </div>

      <div class="col-sm-12" style="margin-top:40px;"></div>
      <div class="col-sm-12" style="font-size:36px;"><dl><dt>帳號資訊</dt></dl></div>
      <div class="col-sm-12" style="background-color:gray; height:3px"></div>
      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">Account :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_account" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="edit_nicknameOnClick()" onfocusout="inputLostFocus(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($account)?></div>
      <div class="col-sm-12" style="background-color:gray; height:1px;"></div>

      <div class="col-sm-2" style="text-align:right; font-size:28px; padding:10px;">Password :</div>
      <div class="col-sm-10" style="text-align:left; font-size:28px; padding:10px;" id="edit_password" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="edit_nicknameOnClick()" onfocusout="inputLostFocus(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)" ><?php echo($password)?></div>
      
      <div class="col-sm-12" style="margin-top:40px; text-align:right;">
        <input type="button" class="btn" id="editPassword" name="editPassword" style="margin-right:100px; height:40px; width:100px;" value="修改密碼" onclick="editPasswordOnClick(this.id)"> 
        <input type="button" class="btn" id="finishPassword" name="finishPassword" style="margin-right:100px; height:40px; width:100px; display:none;" value="確認送出" onclick="finishOnClick()">
      </div>

    </div>

  </div>


</body>
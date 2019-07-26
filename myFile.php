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

$email = $_COOKIE['ck_account'] ;
$password = $_COOKIE['ck_password'] ;
$email_account = getEmailAccount($email) ;

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$fields = "*" ;
$db = "user_account" ;
$where = "email = '$email' and password = '$password'" ;
$result = $conn->get_select($db,$fields,$where) ;
$conn = mySQL_connection::stop_connected();

if ( !isset($result) || empty($result) ) {
    header("Location: sign_in.php") ;
    die() ;
}

$user_nickname = $result[0]['nickname'] ;
if ( $result[0]['picture'] == "none" ) {$user_picture = "Nobody.jpg" ; }
else { $user_picture = $result[0]['picture'] ; }

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$t = $time . "now" ;
$result = $conn->update_data('user_account',"last_login='$t'","eaccount='$email_account'") ;
$fields = "*" ;
$myfiledb = 'user_files' ;
$where = 'owner='.$email_account ;
$fileList = $conn->get_select($myfiledb,$fields) ;
$conn = mySQL_connection::stop_connected();

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
</head>



<body style="background-color:#EDEDED">

    <script type="text/javascript">

        window.onbeforeunload = function() { 
          var htp = new XMLHttpRequest() ;
          var dt = new Date() ;
          var time = dt.getFullYear()+"-"+(dt.getMonth()+1)+"-"+dt.getDate()+" "+dt.getHours()+":"+dt.getMinutes() ;
          var eac = document.getElementById("eaccount").innerHTML ;
          htp.open("GET","msg_reply.php?did=8&t="+time+"&eac="+eac,false) ;
          htp.send() ; 
        } 

        function logoutOnClick() {
            document.cookie = "loging_out = true" ;
            window.location.href = 'sign_in.php' ;
        }

        function myButtonOnMouse(x) {
          document.getElementById(x).style.backgroundColor = "snow" ;
        }

        function myButtonOutMouse(x) {
           document.getElementById(x).style.backgroundColor = "#EDEDED" ;
        }

        function myButtonDownMouse(x) {
          document.getElementById(x).style.backgroundColor = "lightgray" ;
        }

        function myButtonUpMouse(x) {
          document.getElementById(x).style.backgroundColor = "#EDEDED" ;
        }

        function myInformationOnClick() {
          window.location.replace("user_edit_information.php") ;
        }

        function myPictureOnClick() {
          window.location.replace('myPicture.php') ;
        }

        function myFriendOnClick() {
          window.location.replace('myFriend.php') ;
        }

        function myMessageOnClick() {
          window.location.replace("message_board.php") ;
        }

        function ALLMemberOnClick() {
          window.location.href = 'list_user_account.php' ;
        }

        function confirmOnClick() {
            if (!document.getElementById('upload_field').value ) {
                alert('選擇至少一個檔案') ;
            }else {
                var form = document.forms['fileForm'] ;
                form.submit() ;
            }
        }

        function fileOnChange(input) {
            if ( input.files && input.files[0] ) {
                var show = '' ;
                var len = input.files.length ;
                for ( i = 0 ; i < len ; i++ ) show += input.files[i].name+'<br>' ;
                document.getElementById('show_uploads').innerHTML = show ;
            }
        }

        var name = null ;
        var ext = null ;
        function nameOnClick(id) {
            document.getElementById(id).contentEditable = 'true' ;
            name = document.getElementById(id).innerHTML ;
            ext = name.split('.').pop() ;
        }

        function nameOnChange(id){
            document.getElementById(id).contentEditable = 'false' ;
            var now = document.getElementById(id).innerHTML + '.' + ext ;
            if ( now.length == 0 ) {
                document.getElementById(id).innerHTML = name ;
            }else {
                document.getElementById(id).innerHTML += '.' + ext ;
                var htp = new XMLHttpRequest() ;
                var n = null ;
                htp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200 ) {
                        n = this.responseText ;
                    }
                }
                htp.open("GET","file_rename.php?old="+name+"&now="+now) ;
                htp.send() ;
            }
        }
        
    </script>

    <div class="container=fluid">
        <div class="row" style="height:80px; background-color:royalblue">
        <div class="col-sm-12" style="display:none;" id="eaccount"><?php echo(getEmailAccount($email)); ?></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h1 style="color:cornsilk" id="nname"><dl><dt><?php echo($user_nickname);?></dt></dl></h1>
        </div>
        <div class="col-sm-1">
            <input type="button" class="btn" id="log_out" name="log_out" value="登出" style="background-color:royalblue; color:white; font-size:32px; height:80px; text-align:center;" onclick="logoutOnClick()">
        </div>
        </div>
        <div class = "col-sm-3" style = "height:max-content; text-align:center;" id="user_interface" name="user_interface">
        <img src=<?php echo($user_picture);?> class="img-circle img-responsive" width="324" height="368" style="margin:0px auto;" id="pic">
            <br>
            <div style="color:black; font-size:26px;" id="myInformation" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myInformationOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>個人資訊</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myFile" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myMessageOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>留言板</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myPicture" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myPictureOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的照片</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myFriend" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFriendOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>好友列表</dt></dl></div>
            <div style="color:black; font-size:26px;" id="allMember" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="ALLMemberOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>成員列表</dt></dl></div>
        </div>
        <div class="col-sm-9">
            <h1><dl><dt>檔案列表</dt></dl></h1>
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>size</th>
                        <th>update</th>
                        <th>download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ( $fileList ) {
                        $i = $j = 0 ;
                        foreach ( $fileList as $file ) {
                            $size = ceil($file['size']/1024) ;
                            $dlink = $file['download_link'] ;
                            switch ( $i ) {
                                case 0 : 
                                   echo "<tr>" ;
                                   echo "<td id='file" . $j . "' contentEditable='false' onclick='nameOnClick(this.id)' onblur='nameOnChange(this.id)'>" . $file['name'] . "</td>" ;
                                   if ( $size < 1024 ) echo "<td>" . $size . " KB</td>" ;
                                   else echo "<td>" . ceil($size/1024) . " MB</td>" ;
                                   echo "<td>" . $file['upload_time'] . "</td>" ;
                                   echo "<td><a href='" . $dlink . "'>下載</a></td>" ;  
                                   echo "</tr>" ;
                                   $i = 1 ;
                                   $j++ ;
                                   break ;
                                case 1 :
                                   echo "<tr>" ;
                                   echo "<td id='file" . $j . "' contentEditable='false' onclick='nameOnClick(this.id)' onblur='nameOnChange(this.id)'>" . $file['name'] . "</td>" ;
                                   if ( $size < 1024 ) echo "<td>" . $size . " KB</td>" ;
                                   else echo "<td>" . ceil($size/1024) . " MB</td>" ;
                                   echo "<td>" . $file['upload_time'] . "</td>" ;
                                   echo "<td><a href='" . $dlink . "'>下載</a></td>" ; 
                                   echo "</tr>" ;
                                   $i = 0 ;
                                   $j++ ;
                                   break ;
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <div class="col-sm-12" style="font-size:24px;">上傳檔案(最大不超過15MB) :</div>
            <div class="col-sm-12" style="font-size:24px;" id="show_uploads"></div>
            <form class="form-horizontal" id="fileForm" name="fileForm" action="file_uploading.php" method="post" enctype="multipart/form-data">
                <div class="col-sm-5" style="text-align:right;">
                <label id="selectFile" class="btn btn-info">
                    <input type="file" multiple id="upload_field" name="upload_field[]" style="display:none;" onchange="fileOnChange(this)" targetID="show_uploads">
                    <i class="fa fa-cloud-upload" style="width:200px; height:20px; font-size:24px;" >選擇檔案</i>
                </label>
                </div>
                <div class="col-sm-7">
                <label id="confirm" class="btn btn-info" onclick="confirmOnClick()">
                    <i class="fa fa-cloud-upload" style="width:200px; height:20px; font-size:24px;" >確認上傳</i>
                </label>
                </div>
            </form> 
        </div>
    </div>

</body>

</html>
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
$my_eac = getEmailAccount($email) ;


$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$t = $time . "now" ;
$result = $conn->update_data('user_account',"last_login='$t'","eaccount='$my_eac'") ;
$fields = "*" ;
$db = "user_account" ;
$where = "email = '$email' and password = '$password'" ;
$result = $conn->get_select($db,$fields,$where) ;
$conn = mySQL_connection::stop_connected();


$user_nickname = $result[0]['nickname'] ;
$folder = getEmailAccount($email) ;
if ( $result[0]['picture'] == "none" ) {$user_picture = "Nobody.jpg" ; }
else { $user_picture = $result[0]['picture'] ; }
setcookie('nickname') ;
setcookie('picture') ;

if ( isset($_COOKIE['deleteURL'])) {
    unlink(substr($_COOKIE['deleteURL'],34)) ;
    setcookie('deleteURL') ;
}

$pics = glob('user_pictures/' . $folder . '/*.*') ;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pictures</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
            window.location.href('sign_in.php') ;
        }
    </script>

    <script type="text/javascript">
        function myButtonOnMouse(x) {
          document.getElementById(x).style.backgroundColor = "snow" ;
        }
    </script>

    <script type="text/javascript">
        function myButtonOutMouse(x) {
           document.getElementById(x).style.backgroundColor = "#EDEDED" ;
        }
    </script>

    <script type="text/javascript">
        function myButtonDownMouse(x) {
          document.getElementById(x).style.backgroundColor = "lightgray" ;
        }
    </script>

    <script type="text/javascript">
        function myButtonUpMouse(x) {
          document.getElementById(x).style.backgroundColor = "#EDEDED" ;
        }
    </script>

    <script type="text/javascript">
        function myInformationOnClick() {
          window.location.replace("user_edit_information.php") ;
        }
    </script>

    <script type="text/javascript">
        function myFileOnClick() {
          window.location.replace('myFile.php') ;
        }
    </script>

    <script type="text/javascript">
        function myFriendOnClick() {
          window.location.replace('myFriend.php') ;
        }

        function ALLMemberOnClick() {
          window.location.href = 'list_user_account.php' ;
        }
    </script>

    <script type="text/javascript">
        function myMessageOnClick() {
          window.location.replace("message_board.php") ;
        }
    </script>

    <script type="text/javascript">
        function uploadPic(input) {
            if ( input.files && input.files[0] ) {
                var form = document.forms['picForm'] ;
                document.cookie = 'nickname='.concat(document.getElementById("nname").innerHTML) ;
                document.cookie = 'picture='.concat(document.getElementById("pic").src) ;
                form.submit() ;
            }
        }
    </script>

    <script type="text/javascript">
        function myUpOnMouse() {
            document.getElementById("addNew").src = "plusOnMouse.jpg" ;
        }
        function myUpOutMouse() {
            document.getElementById("addNew").src = "plus.jpg" ;
        }
        function myUpDownMouse() {
            document.getElementById("addNew").src = "plusOnMouse.jpg" ;
        }
        function myUpUpMouse() {
            document.getElementById("addNew").src = "plus.jpg" ;
        }
    </script>

    <script type="text/javascript">
        function picOnClickDelete(x) {
            if ( confirm('要刪除這張照片嗎 ?') ) {
                var string = document.getElementById(x).src ;
                var icon = document.getElementById('pic').src ;
                if ( string == icon ) {
                    alert('不能刪除大頭貼使用的照片') ;
                    return ;
                }
                document.cookie = 'deleteURL='.concat(document.getElementById(x).src) ;
                document.cookie = 'nickname='.concat(document.getElementById('nname').innerHTML) ;
                document.cookie = 'picture='.concat(document.getElementById('pic').src) ;
                window.location.replace('myPicture.php') ;
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
            <div style="color:black; font-size:26px;" id="myPicture" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFileOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的檔案</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myFriend" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFriendOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>好友列表</dt></dl></div>
            <div style="color:black; font-size:26px;" id="allMember" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="ALLMemberOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>成員列表</dt></dl></div>
        </div>
        <div class="col-sm-9">
        <?php 
        $array = null ;
        $i = 0 ;
        foreach( $pics as $pic ) {
            $array = getimagesize($pic) ;
            if ( $array[0] <= $array[1] ) echo '<img src=' . $pic .' class="img-fluid" width="364" height="auto" id="pic'.$i.'" onclick="picOnClickDelete(this.id)">' ;
            else echo '<img src=' . $pic .' class="img-fluid" height="364" width="auto" id="pic'.$i.'" onclick="picOnClickDelete(this.id)">' ;
            $i++ ;
        }
        ?>
        <form class="form" id="picForm" name="picForm" action="picture_uploading.php" method="post" enctype="multipart/form-data">
            <label id="lab" onmouseover="myUpOnMouse()" onmouseout="myUpOutMouse()" onmousedown="myUpDownMouse()" onmouseup="myUpUpMouse()">
                <input type="file" multiple id="upload_field" name="upload_field[]" accept="image/gif,image/jpeg,image/png,image/jpg" style="display:none;" onchange="uploadPic(this)">
                <img src='plus.jpg' class='img-fluid' width="300" height="auto" id="addNew">
            </label>
        </div>
    </div>

</body>

</html>
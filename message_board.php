<?php 
session_start() ;
date_default_timezone_set("Asia/Taipei"); 
$time = date('Y-n-d G:i') ;
?>

<?php
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

if ( isset($_SESSION['keep_name']) ) {
  $email = $_SESSION['keep_name'] ;
  $password = $_SESSION['keep_password'] ;
}else if ( isset($_COOKIE['ck_account']) ){
  $email = $_COOKIE['ck_account'] ;
  $password = $_COOKIE['ck_password'] ;
}else {
  header("Location: sign_in.php") ;
  echo "Login Information error" ;
  die() ;
}
setcookie('ck_account',$email) ;
setcookie('ck_password',$password) ;
$my_eac = getEmailAccount($email) ;

$user_picture = "Nobody.jpg" ;

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;

//update login time
$t = $time . "now" ;
$result = $conn->update_data('user_account',"last_login='$t'","eaccount='$my_eac'") ;

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
if ( $result[0]['picture'] != "none" ) $user_picture = $result[0]['picture'] ;

$my_friend = $result[0]['friends'] ;
$tem = '' ;
$my_friends[] = null ;
$i = $j = 0 ;
for ( $i = 0 ; $i < strlen($my_friend) ; $i++ ) {
  if ( $my_friend[$i] == ',' ) {
    $my_friends[$j] = $tem ;
    $tem = '' ;
    $j++ ;
  }else $tem .= $my_friend[$i] ;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>MessageBox</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' rel='stylesheet'>

  <style>
    input:focus , textarea:focus{
      outline : none ;
    }

    .styled-select select {
      background: transparent;
      width: 300px;
      padding: 0 0 0 90px;
      font-size: 24px;
      height: 20px;
      -webkit-appearance: none;
      font-family: 'FontAwesome' , 'item' ;
    }

    .styled-select {
      width: 20px;
      height: 20px;
      overflow: hidden;
    }

  </style>

</head>

<body style="background-color:#EDEDED;">

  <script type="text/javascript">
    window.onbeforeunload = function() { 
      var htp = new XMLHttpRequest() ;
      var dt = new Date() ;
      var time = dt.getFullYear()+"-"+(dt.getMonth()+1)+"-"+dt.getDate()+" "+dt.getHours()+":"+dt.getMinutes() ;
      var eac = document.getElementById("eaccount").innerHTML ;
      htp.open("GET","msg_reply.php?did=8&t="+time+"&eac="+eac,false) ;
      htp.send() ; 
    } 
  </script>

  <script type="text/javascript">
    function logoutOnClick() {
      document.cookie = "loging_out = true" ;
      window.location.replace('sign_in.php') ;
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
      window.location.href = 'user_edit_information.php' ;
    }
  </script>

  <script type="text/javascript">
    function myFileOnClick() {
      window.location.replace('myFile.php') ;
    }

    function ALLMemberOnClick() {
      window.location.href = 'list_user_account.php' ;
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

  <script type="text/javascript">
    function goodOnClick(x) {
      var id = parseInt(x).toString() ;
      var what = null ;
      var n = null ;
      if ( document.getElementById(x).style.color != "blue" ) {
        document.getElementById(x).style.color = "blue" ;
        //n = parseInt(document.getElementById(id+'numberGoods').innerHTML) + 1 ;
        //document.getElementById(id+'numberGoods').innerHTML = n ; 
        //if ( n >= 1 ) document.getElementById(id+'numberGoods').style.display = "initial" ;
        what = 1 ;
      }
      else {
        document.getElementById(x).style.color = "gray" ;
        //n = parseInt(document.getElementById(id+'numberGoods').innerHTML) - 1 ;
        //document.getElementById(id+'numberGoods').innerHTML = n ; 
        //if ( n <= 0 ) document.getElementById(id+'numberGoods').style.display = "none" ;
        what = 0 ;
      }

      var who = document.getElementById('eaccount').innerHTML ;
      var htp = new XMLHttpRequest() ;
      htp.onreadystatechange = function() {
        if (htp.readyState == 4 && htp.status == 200 ) {
          n = htp.responseText ;
        }
      } ;
      //important : only can n get value when we use async(set to false) ;
      htp.open("GET","msg_reply.php?did=2&pid="+id+"&eaccount="+who+"&gdy="+what,false) ;
      htp.send() ;

      document.getElementById(id+'numberGoods').innerHTML = n ;
      if ( n >= 1 ) document.getElementById(id+'numberGoods').style.display = "initial" ;
      else document.getElementById(id+'numberGoods').style.display = "none" ;
    }

    function replyOnClick(x) {
      var id = parseInt(x).toString() ;
      if ( document.getElementById(id+"reply_board").style.display == "initial" ) return ;
      document.getElementById(id+"reply_divline").style.display = "initial" ;
      document.getElementById(id+"reply_board").style.display = "initial" ;
      document.getElementById(id+"input_reply").style.display = "initial" ;

      var htp = new XMLHttpRequest() ;
      var rep = null ;
      htp.onreadystatechange = function() {
        if ( htp.readyState == 4 && htp.status == 200 ) rep = htp.responseText ;
      } ;
      htp.open("GET","msg_reply.php?did=4&pid="+id,false) ;
      htp.send() ;

      if ( rep == null || rep == "" || rep == " " ) return ;
      var i = 0 , j = 0 , count = 0 ;
      var kind = 0 ;                        // 0 = email account , 1 = reply content , 2 = time , 3 = output a reply
      var ueac = null , cont = null , retime = null , tem = null ;
      var htp2 = null , pic = null , unname = null ;
      for ( i = 0 ; i < rep.length ; i++ ) {
        if ( rep[i] == '*' ) {
          switch ( kind ) {
            case 0 :
              ueac = rep.substr(j,i-j) ;
              j = i + 1 ;
              kind = 1 ;
              break ;
            case 1 :
              cont = rep.substr(j,i-j) ;
              j = i + 1 ;
              kind = 2 ;
              break ;
            case 2 :
              retime = rep.substr(j,i-j) ;
              j = i + 1 ;
              kind = 3 ;
              break ;              
          }
        }else continue ;
        if ( kind < 3 ) continue ;
        else kind = 0 ;

        htp2 = new XMLHttpRequest() ;
        htp2.onreadystatechange = function () {
          if ( htp2.readyState == 4 && htp2.status == 200 ) tem = htp2.responseText ;
        }
        htp2.open("GET","msg_reply.php?did=5&eac="+ueac,false) ;
        htp2.send() ;

        var opop = id.toString()+'numberReplies' ;
        pic = tem.substr(0,tem.indexOf(' ')) ;
        unname = tem.substr(tem.indexOf(' ')+1) ;
        tem = unname + ":" + cont ;
        document.getElementById(id+"reply_board").innerHTML += '<div class="col-sm-2" id="'+id.toString()+count.toString()+'src" style="margin-top:10px;"><img src="'+pic+'" class="img-circle img-responsive" width="60" height="60" style="margin:0px auto;"></div>' ;
        document.getElementById(id+"reply_board").innerHTML += '<div class="col-sm-10" id="'+id.toString()+count.toString()+'reply" style="font-size:17px; margin-top:10px;" onclick="replyijOnClick(this.id)">'+tem+'<br>'+retime+'</div>' ;
        document.getElementById(id+"reply_board").innerHTML += '<div class="col-sm-12" id ="'+id.toString()+count.toString()+'getid" style="display:none;">'+id+'</div>' ;
        document.getElementById(id+"reply_board").innerHTML += '<div class="col-sm-12" id ="'+id.toString()+count.toString()+'geteac" style="display:none;">'+ueac+'</div>' ;
        count ++ ;

      }

    }

    function msgMouseDown(x){
      document.getElementById(x).style.fontSize = "18px" ;
    }

    function msgMouseUp(x) {
      document.getElementById(x).style.fontSize = "20px" ;
    }

    function submitreplyOnClick(x){
      var id = parseInt(x).toString() ;
      var newReply = document.getElementById(id+"edit_reply").value ;
      if ( newReply.length <= 0 ) return ;
      document.getElementById(id+"edit_reply").value = "" ;
      var src = document.getElementById('uimg').src ;
      var myname = document.getElementById('nname').innerHTML ;
      var dt = new Date() ;

      //insert to database
      var owner = document.getElementById('eaccount').innerHTML ;
      var htp = new XMLHttpRequest() ;
      var n = null ;
      htp.onreadystatechange = function() {
        if (htp.readyState == 4 && htp.status == 200 ) {
          n = htp.responseText ;
        }
      } ;
      //important : only can n get value when we use async(set to false) ;
      htp.open("GET","msg_reply.php?did=1&pid="+id+"&cont="+newReply+"&eac="+owner,false) ;
      htp.send() ;

      var s1 = '<div class="col-sm-2" id="'+id+n.toString()+'" style="margin-top:10px;"><img src="'+src+'" class="img-circle img-responsive" width="60" height="60" style="margin:0px auto;" id="'+id+n.toString()+'preparesrc"></div>' ;
      var s2 = '<div class="col-sm-10" id="'+id+n.toString()+'preparereply" style="font-size:17px; margin-top:10px;" onclick="replyijOnClick(this.id)"></div>' ;
      document.getElementById(id+'reply_board').innerHTML += s1 + s2 ;
      document.getElementById(id+n.toString()+'preparereply').innerHTML = myname+":"+newReply+"<br>"+dt.getFullYear()+"-"+(dt.getMonth()+1)+"-"+dt.getDate()+" "+dt.getHours()+":"+dt.getMinutes() ;
      var k = ( document.getElementById(id+'numberReplies').innerHTML == "" ? 0 : parseInt(document.getElementById(id+'numberReplies').innerHTML)+1 ) ;
      document.getElementById(id+'numberReplies').innerHTML = k + "則留言" ;
      document.getElementById(id+'numberReplies').style.display = 'initial' ;
    }

    function replyijOnClick(x) {
      var id = parseInt(x).toString() ;
      var replyer = '' ;
      var string = document.getElementById(x).innerHTML ;
      for ( var i = 0 ; i < string.length ; i++) {
        if ( string[i] == ":" ) break ;
        else replyer += string[i] ;
      }

      var parentid = document.getElementById(id+"getid").innerHTML ;
      var myname = document.getElementById("nname").innerHTML ;
      if ( myname != replyer ) return ;
      else {
        if ( confirm('要刪除回覆嗎?') ) {
          document.getElementById(id+'src').style.display = "none" ;
          document.getElementById(id+'reply').style.display = "none" ;
          var str = document.getElementById(id+'reply').innerHTML ;
          str = str.substr(str.indexOf(":")+1) ;
          str = document.getElementById(id+'geteac').innerHTML + '*' + str.replace(/<br>/,"*") + '*' ;

          var htp = new XMLHttpRequest() ;
          var n = null ;
          htp.onreadystatechange = function() {
            if ( htp.readyState == 4 && htp.status == 200 ) n = htp.responseText ;
          }
          htp.open("GET","msg_reply.php?did=6&cont="+str+"&pid="+parentid,false) ;
          htp.send() ;

          if ( n == 0 ) document.getElementById(parentid+'numberReplies').innerHTML = "" ;
          else document.getElementById(parentid+'numberReplies').innerHTML = n + "則留言" ;
        }else {
          return ;
        }
      }
    }

    function build_postOnClick(){
      document.getElementById('build_post').rows = "6" ;
      document.getElementById('postReady').style.display = "initial" ;
    }

    function postOutFocus() {
      setTimeout("postLostFocus()",500);
    }

    function postLostFocus() {
      document.getElementById('build_post').rows="1" ;
      document.getElementById('postReady').style.display = "none" ;
    }

    function postOnClick() {
      var newpostcontent = document.getElementById('build_post').value ;
      if ( newpostcontent.length == 0 ) return ;
      document.getElementById('build_post').value = "" ;

      //first store to database
      var owner = document.getElementById('eaccount').innerHTML ;
      var htp = new XMLHttpRequest() ;
      var n = null ;
      var belong = document.getElementById('border_eaccount').innerHTML ;
      htp.onreadystatechange = function() {
        if (htp.readyState == 4 && htp.status == 200 ) {
          n = htp.responseText ;
        }
      } ;
      //important : only can n get value when we use async(set to false) ;
      htp.open("GET","msg_reply.php?did=0&owner="+owner+"&content="+newpostcontent+"&be="+belong,false) ;
      htp.send() ;

      var src = document.getElementById('uimg').src ;
      var nickname = document.getElementById('nname').innerHTML ;
      var dt = new Date() ;
      var time = dt.getFullYear()+"-"+(parseInt(dt.getMonth())+1).toString()+"-"+dt.getDate()+" "+dt.getHours()+":"+dt.getMinutes() ; 

      document.getElementById('message_board').innerHTML += '<div class="col-sm-12" style="height:max-content; background-color:snow; border-radius:18px; margin-top:20px;" id="'+n+'post"></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-2" style="margin-top:10px;"><img src="'+src+'" class="img-circle img-responsive" width="70" height="70" style="margin:0px auto;"></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-4" style="font-size:18px; margin-top:10px;"><dt>'+nickname+'</dt>'+time+'</div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-5" style="margin-top:10px;"></div>' ;

      document.getElementById(n+'post').innerHTML += '<div class="col-sm-5" style="margin-top:10px;"></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-1" style="margin-top:10px; position:relative; text-align:center;"><div style="z-index:1; position:absolute;"><i class="fa fa-chevron-down fa-lg" style="color:lightgray;"></i></div><div style="z-index:2; position:absolute;" class="styled-select"><Select id="'+n+'do_what" style="opacity:0; z-index:1; position:absolute;" onchange="postDoOnChange(this.id)" ><Option value="edit_post" >&#xf040;編輯貼文</Option><Option value="delete_post" >&#xf00d;刪除貼文</Option><option value="nothing" style="display:none;" selected>NOthing</option></select></div></div>' ;
      /*
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-1" style="margin-top:10px; position:relative; text-align:center;" id="'+n+'pos"></div>' ;
      document.getElementById(n+'pos').innerHTML += '<div style="z-index:1; position:absolute;"><i class="fa fa-chevron-down fa-lg" style="color:lightgray;"></i></div>' ;
      document.getElementById(n+'pos').innerHTML += '<div style="z-index:2; position:absolute;" class="styled-select" id="'+n+'pos1"></div>' ;
      document.getElementById(n+'pos1').innerHTML += '<Select id="'+n+'do_what" style="opacity:0; z-index:1; position:absolute;" onchange="postDoOnChange(this.id)" >' ;
      document.getElementById(n+'pos1').innerHTML += '<Option value="edit_post" >&#xf040;編輯貼文</Option>' ;
      document.getElementById(n+'pos1').innerHTML += '<Option value="delete_post" >&#xf00d;刪除貼文</Option>' ;
      document.getElementById(n+'pos1').innerHTML += '<option value="nothing" style="display:none;" selected>NOthing</option></select>' ;
*/
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-12" style="font-size:18px; margin-top:15px; outline:none;" id="'+n+'content"></div>' ;
      document.getElementById(n+'content').innerHTML = newpostcontent ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-12" style="margin-top:15px; display:none;" id="'+n+'editingButton"><input type="button" id="'+n+'editSure" class="btn btn-primary" value="確認" style="float:left;" onclick="editFinishOnClick(this.id)"><input type="button" id="'+n+'editCancel" class="btn btn-primary" value="取消" style="float:right;" onclick="editCancelOnClick(this.id)"></div>' ; 
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-6" style="font-size:15px; margin-top:15px; color:gray;" id="'+n+'goods"></div>' ;
      document.getElementById(n+'goods').innerHTML += '<i class="fa fa-thumbs-o-up" style="font-size:15px; display:none;" id="'+n+'numberGoods">0</i>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-6" style="font-size:15px; margin-top:15px; text-align:right; color:gray; display:none;" id="'+n+'numberReplies">0則留言</div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-12" style="margin-top:10px; height:1px; background-color:lightgray;"></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-6" id="'+n+'good" style="font-size:20px; padding:12px; text-align:center; color:gray;" onclick="goodOnClick(this.id)" onmousedown="msgMouseDown(this.id)" onmouseup="msgMouseUp(this.id)"><i class="fa fa-thumbs-o-up">讚</i></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-6" id="'+n+'reply" style="font-size:20px; padding:12px; text-align:center; color:gray;" onclick="replyOnClick(this.id)" onmousedown="msgMouseDown(this.id)" onmouseup="msgMouseUp(this.id)"><i class="fa fa-commenting-o">回覆</i></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-12" id="'+n+'reply_divline" style="height:1px; background-color:lightgray; display:none;"></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-12" id="'+n+'reply_board" style="margin-top:10px; margin-bottom:10px; display:none;"></div>' ;
      document.getElementById(n+'post').innerHTML += '<div class="col-sm-12" id="'+n+'input_reply" style="margin-top:10px; margin-bottom:10px; display:none;"></div>' ;
      document.getElementById(n+'input_reply').innerHTML += '<div class="col-sm-11"><input type="text" class="col-sm-12" id="'+n+'edit_reply" maxlength="200" style="background-color:snow; border-color:#EDEDED; border-radius:18px; padding:8px; font-size:18px;" placeholder="留言..."></div>' ;
      document.getElementById(n+'input_reply').innerHTML += '<div class="col-sm-1"><input type="button" id="'+n+'submit_reply" class="btn btn-primary" style="border-radius:18px; outline:none; font-size:18px;" value="送出" onclick="submitreplyOnClick(this.id)"></div>' ;

    }

    var originPostContent = null ;
    function postDoOnChange(x) {
      var id = parseInt(x).toString() ;
      var postID = id+'content' ;
      var work = document.getElementById(x).value ;
      if ( work == 'edit_post' ) {
        originPostContent = document.getElementById(postID).innerHTML ;
        document.getElementById(postID).contentEditable = true ;
        document.getElementById(id+'editingButton').style.display = "initial" ;
      }else if ( work == 'delete_post') {
        if ( confirm("確定要刪除這篇貼文嗎 ?")) {
          var htp = new XMLHttpRequest() ;
          var ser = null ;
          htp.onreadystatechange = function() {
            if ( htp.readyState == 4 && htp.status == 200 ) ser = htp.responseText ;
          }
          htp.open("GET",'msg_reply.php?did=7&pid='+id,false) ;
          htp.send() ;

          if ( ser == "success") document.getElementById(id+'post').style.display = "none" ;
          else alert("連線錯誤，無法刪除") ;
        }
      }else {
        return ;
      }

      document.getElementById(x).options[2].selected = true ;
    }

    function editFinishOnClick(x) {
      var id = parseInt(x).toString() ;
      var postID = id+'content' ;
      if ( confirm('確定更新貼文嗎?')) {
        document.getElementById(postID).contentEditable = false ;
        document.getElementById(id+'editingButton').style.display = "none" ;
        var pst = document.getElementById(postID).innerHTML ;

        if ( pst != originPostContent ) {
          var htp = new XMLHttpRequest() ;
          var ser = null ;
          htp.onreadystatechange = function() {
            if ( htp.readyState == 4 && htp.status == 200 ) ser = htp.responseText ;
          }
          htp.open("GET",'msg_reply.php?did=3&pid='+id+'&cont='+pst,false) ;
          htp.send() ;
        }else {
          originPostContent = null ;
          return ;
        }

        if ( ser != "error" ) {
          originPostContent = null ;
          document.getElementById(id+'post_time').innerHTML = "<dt>" + document.getElementById('nname').innerHTML + "</dt>" + ser ;
        }else {
          alert("連線失敗，請檢察網路連線") ;
          document.getElementById(postID).innerHTML = originPostContent ;
          originPostContent = null ;
        }

      }
    }

    function editCancelOnClick(x) {
      var id = parseInt(x).toString() ;
      var postID = id+'content' ;
      if ( confirm('要放棄編輯嗎?')) {
        document.getElementById(postID).contentEditable = false ;
        document.getElementById(id+'editingButton').style.display = "none" ;
        document.getElementById(postID).innerHTML = originPostContent ;
      } else return ;
      originPostContent = null ;
    }

  </script>

  <div class="container-fluid">
    <div class="row" style="height:80px; background-color:royalblue" >
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <div class="col-sm-12" style="display:none;" id="nname"><?php echo($user_nickname);?></div>
        <div class="col-sm-12" style="display:none;" id="eaccount"><?php echo(getEmailAccount($email)); ?></div>
        <h1 style="color:cornsilk"><dl><dt><?php echo($user_nickname);?></dt></dl></h1>
      </div>
      <div class="col-sm-1">
        <input type="button" class="btn" id="log_out" name="log_out" value="登出" style="background-color:royalblue; color:white; font-size:32px; height:80px; text-align:center;" onclick="logoutOnClick()">
      </div>
    </div>
    <br>      
    <div class = "col-sm-3" style = "height:max-content; text-align:center;" id="user_interface" name="user_interface">
      <img src=<?php echo($user_picture);?> class="img-circle img-responsive" width="324" height="368" style="margin:0px auto;" id="uimg">
      <br>
      <div style="color:black; font-size:26px;" id="myInformation" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myInformationOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>個人資訊</dt></dl></div>
      <div style="color:black; font-size:26px;" id="myFile" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFileOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的檔案</dt></dl></div>
      <div style="color:black; font-size:26px;" id="myPicture" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myPictureOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的照片</dt></dl></div>
      <div style="color:black; font-size:26px;" id="myFriend" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFriendOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>好友列表</dt></dl></div>
      <div style="color:black; font-size:26px;" id="allMember" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="ALLMemberOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>成員列表</dt></dl></div>
    </div>
    <div class = "col-sm-6" style="height:max-content" id="message_board" name="message_board">
      <div class="col-sm-12" style="height:max-content; background-color:snow; border-radius:18px;" id="newpost">
        <div class="col-sm-12" style="font-size:18px; color:gray; margin-top:10px; font-weight:bold;">建立貼文</div>
        <div class="col-sm-12" style="height:1px; background-color:lightgray; margin-top:10px;"></div>
        <div class="col-sm-2" style="margin-top:10px; margin-bottom:10px;"><img src="<?php echo($user_picture);?>" class="img-circle img-responsive" width="70" height="70" style="margin:0px auto;"></div>
        <div class="col-sm-10" style="margin-top:10px; margin-bottom:10px" onclick="build_postOnClick()" onfocusout="postOutFocus()">
          <textarea class="col-sm-12" id="build_post" rows="1" style="resize:none; border-width:0px; font-size:18px;" placeholder="在想什麼?" maxlength="490"></textarea>
        </div>
        <div class="col-sm-12" style="text-align:right; margin-bottom:10px;">
          <input type="button" class="btn btn-primary" id="postReady" onclick="postOnClick(this.id)" value="發佈" style="font-size:16px; display:none; width:90px; border-radius:18px; outline:none;">
        </div>
      </div>
      <?php
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
        if ( isset($_GET['eac']) ) $user_eac = $_GET['eac'] ;
        else $user_eac = getEmailAccount($_COOKIE['ck_account']) ;

        echo '<div class="col-sm-12" style="display:none;" id="border_eaccount">' . $user_eac . '</div>' ;
        if ( $user_eac != $my_eac ) {
           $Name = $conn->get_select('user_account','nickname',"eaccount='$user_eac'") ;
           $user_nname = $Name[0]['nickname'] ;
           echo '<div class="col-sm-12" style="font-size:32px; font-weight:bold; margin-top:20px; background:snow; padding:12px; border-radius:18px; padding-left:20px;">'.$user_nname.'的留言板</div>' ;
        }
        
        $fields = "*" ;
        $db = "user_posts" ;
        $where = "board = '$user_eac'" ;
        $result = $conn->get_select($db,$fields,$where) ;

        //if post exist , print out
        if ( isset($result) && !empty($result) ) {

          foreach( $result as $post ) {
            $res = $conn->get_select('user_account','picture,nickname','eaccount="'.$post['owner'].'"') ;
            if ( strpos($post['say_good'],$my_eac) ) $goodColor = "blue" ;
            else $goodColor = "gray" ;
            echo '<div class="col-sm-12" style="height:max-content; background-color:snow; border-radius:18px; margin-top:20px;" id="'.$post['id'].'post">' ;
            echo    '<div class="col-sm-2" style="margin-top:10px;"><img src="'.$res[0]['picture'].'" class="img-circle img-responsive" width="70" height="70" style="margin:0px auto;"></div>' ;
            echo    '<div class="col-sm-4" style="font-size:18px; margin-top:10px;" id="'.$post['id'].'post_time"><dt>'.$res[0]['nickname'].'</dt>'.$post['post_time'].'</div>' ;

            //on my message board, can edit and delete
            if ( ($post['owner'] == $my_eac) || ($my_eac == "NULL") ) {
            echo    '<div class="col-sm-5" style="margin-top:10px;"></div>' ;
            echo    '<div class="col-sm-1" style="margin-top:10px; position:relative; text-align:center;">' ;
            echo         '<div style="z-index:1; position:absolute;"><i class="fa fa-chevron-down fa-lg" style="color:lightgray;"></i></div>' ;
            echo         '<div style="z-index:2; position:absolute;" class="styled-select">' ;
            echo         '<Select id="'.$post['id'].'do_what" style="opacity:0; z-index:1; position:absolute;" onchange="postDoOnChange(this.id)" >' ;
            if ( $post['owner'] == $my_eac ) echo '<Option value="edit_post" >&#xf040;編輯貼文</Option>' ;
            else echo        '<Option value="edit_post" style="display:none;" >&#xf040;編輯貼文</Option>' ;
            echo             '<Option value="delete_post" >&#xf00d;刪除貼文</Option>' ;
            echo             '<option value="nothing" style="display:none;" selected>NOthing</option>' ;
            echo    '</select></div></div>' ;
            }

            echo    '<div class="col-sm-12" style="font-size:18px; margin-top:15px; outline:none;" id="'.$post['id'].'content">'.$post['content'].'</div>' ;
            echo    '<div class="col-sm-12" style="margin-top:15px; display:none;" id="'.$post['id'].'editingButton">' ;
            echo         '<input type="button" id="'.$post['id'].'editSure" class="btn btn-primary" value="確認" style="float:left;" onclick="editFinishOnClick(this.id)">' ;
            echo         '<input type="button" id="'.$post['id'].'editCancel" class="btn btn-primary" value="取消" style="float:right;" onclick="editCancelOnClick(this.id)">' ;
            echo    '</div>' ;

            if ( $post['good'] > 0 ) echo '<div class="col-sm-6" style="font-size:15px; margin-top:15px; color:gray;" id="'.$post['id'].'goods"><i class="fa fa-thumbs-o-up" style="font-size:15px;" id="'.$post['id'].'numberGoods">'.$post['good'].'</i></div>' ;
            else echo '<div class="col-sm-6" style="font-size:15px; margin-top:15px; color:gray;" id="'.$post['id'].'goods"><i class="fa fa-thumbs-o-up" style="font-size:15px; display:none;" id="'.$post['id'].'numberGoods">0</i></div>' ;
            if ( $post['reply'] > 0 ) echo '<div class="col-sm-6" style="font-size:15px; margin-top:15px; text-align:right; color:gray;" id="'.$post['id'].'numberReplies" >'.$post['reply'].'則留言</div>' ;
            else echo '<div class="col-sm-6" style="font-size:15px; margin-top:15px; text-align:right; color:gray; display:none;" id="'.$post['id'].'numberReplies" >0則留言</div>' ;
            echo    '<div class="col-sm-12" style="margin-top:10px; height:1px; background-color:lightgray;"></div>' ;
            echo    '<div class="col-sm-6" id="'.$post['id'].'good" style="font-size:20px; padding:12px; text-align:center; color:'.$goodColor.';" onclick="goodOnClick(this.id)" onmousedown="msgMouseDown(this.id)" onmouseup="msgMouseUp(this.id)"><i class="fa fa-thumbs-o-up">讚</i></div>' ;
            echo    '<div class="col-sm-6" id="'.$post['id'].'reply" style="font-size:20px; padding:12px; text-align:center; color:gray;" onclick="replyOnClick(this.id)" onmousedown="msgMouseDown(this.id)" onmouseup="msgMouseUp(this.id)"><i class="fa fa-commenting-o">回覆</i></div>' ;
            echo    '<div class="col-sm-12" id="'.$post['id'].'reply_divline" style="height:1px; background-color:lightgray; display:none;"></div>' ;
            echo    '<div class="col-sm-12" id="'.$post['id'].'reply_board" style="margin-top:10px; margin-bottom:10px; display:none;">' ;
            echo    '</div>' ;
            echo    '<div class="col-sm-12" id="'.$post['id'].'input_reply" style="margin-top:10px; margin-bottom:10px; display:none;">' ;
            echo        '<div class="col-sm-11"><input type="text" class="col-sm-12" id="'.$post['id'].'edit_reply" maxlength="200" style="background-color:snow; border-color:#EDEDED; border-radius:18px; padding:8px; font-size:18px;" placeholder="留言..."></div>' ;
            echo        '<div class="col-sm-1"><input type="button" id="'.$post['id'].'submit_reply" class="btn btn-primary" style="border-radius:18px; outline:none; font-size:18px;" value="送出" onclick="submitreplyOnClick(this.id)"></div>' ;
            echo    '</div>' ;
            echo '</div>' ;
          }
        }
        
        $conn = mySQL_connection::stop_connected() ;
        
      ?>
      <!---
      <div class="col-sm-12" style="display:none;" id="board_belong"><?php echo(getEmailAccount($user_eac));?></div>
      <div class="col-sm-12" style="height:max-content; background-color:snow; border-radius:18px; margin-top:20px;" id="325post">
        <div class="col-sm-2" style="margin-top:10px;"><img src=<?php echo($user_picture);?> class="img-circle img-responsive" width="70" height="70" style="margin:0px auto;"></div>
        <div class="col-sm-4" style="font-size:18px; margin-top:10px;"><dt><?php echo($user_nickname);?></dt><?php echo($time);?></div>
        <div class="col-sm-5" style="margin-top:10px;"></div>
        <div class="col-sm-1" style="margin-top:10px; position:relative; text-align:center;">
          <div style="z-index:1; position:absolute;"><i class="fa fa-chevron-down fa-lg" style="color:lightgray;"></i></div>
          <div style="z-index:2; position:absolute;" class="styled-select">
          <Select name="language" id='325sel' style="opacity:0; z-index:1; position:absolute; " onchange="postDoOnClick(this.id)" >
            <Option value="edit_post" >&#xf040;編輯貼文</Option>
            <Option value="delete_post" >&#xf00d;刪除貼文</Option>
            <option value="nothing" style="display:none;" selected>NOthing</option>
          </Select>
          </div>
        </div>
        <div class="col-sm-12" style="font-size:18px; margin-top:15px;" id="325content">content here</div>
        <div class="col-sm-12" style="margin-top:15px; display:none;" id="325editingButton">
          <input type="button" id="325editSure" class="btn btn-primary" value="確認" style="float:left;" onclick="editFinishOnClick(this.id)">
          <input type="button" id="325editCancel" class="btn btn-primary" value="取消" style="float:right;" onclick="editCancelOnClick(this.id)">
        </div>
        <div class="col-sm-6" style="font-size:15px; margin-top:15px; color:gray;" id="325goods"><i class="fa fa-thumbs-o-up" style="font-size:15px;" id="325numberGoods">12</i></div>
        <div class="col-sm-6" style="font-size:15px; margin-top:15px; text-align:right; color:gray;" id="325numberReplies" >33則留言</div>
        <div class="col-sm-12" style="margin-top:10px; height:1px; background-color:lightgray;"></div>
        <div class="col-sm-6" id="325good" style="font-size:20px; padding:12px; text-align:center; color:gray;" onclick="goodOnClick(this.id)" onmousedown="msgMouseDown(this.id)" onmouseup="msgMouseUp(this.id)"><i class="fa fa-thumbs-o-up">讚</i></div>
        <div class="col-sm-6" id="325reply" style="font-size:20px; padding:12px; text-align:center; color:gray;" onclick="replyOnClick(this.id)" onmousedown="msgMouseDown(this.id)" onmouseup="msgMouseUp(this.id)"><i class="fa fa-commenting-o">回覆</i></div>
        <div class="col-sm-12" id="325reply_divline" style="height:1px; background-color:lightgray; display:none;"></div>
        <div class="col-sm-12" id="325reply_board" style="margin-top:10px; margin-bottom:10px; display:none;">
          <div class="col-sm-2" id="srcij" style="margin-top:10px;"><img src="amber2.jpg" class="img-circle img-responsive" width="60" height="60" style="margin:0px auto;"></div>
          <div class="col-sm-10" id="replyij" style="font-size:17px; margin-top:10px;" onclick="replyijOnClick(this.id)">帥哥:你好漂釀<br><?php echo($time);?></div>
          
        </div>
        <div class="col-sm-12" id="input_replyi" style="margin-top:10px; margin-bottom:10px; display:none;">
          <div class="col-sm-11"><input type="text" class="col-sm-12" id="325edit_reply" maxlength="200" style="background-color:snow; border-color:#EDEDED; border-radius:18px; padding:8px; font-size:18px;" placeholder="留言..."></div>
          <div class="col-sm-1"><input type="button" id="325submit_reply" class="btn btn-primary" style="border-radius:18px; outline:none; font-size:18px;" value="送出" onclick="submitreplyOnClick(this.id)"></div>
        </div>
      </div>
    </div>
    --->
    <div class = "col-sm-3" style="height:max-content" id="friend_board" name="friend_board">
    <?php
    /*
    $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
    foreach($my_friends as $friend) {
      $fri = $conn->get_select('user_account','id,nickname,picture,last_login',"eaccount='$friend'") ;
      if ( strpos($fri[0]['last_login'],"now") ) $last_log = "上線中" ;
      else $last_log = getTimeInterval($time,$fri[0]['last_login']) ;
      echo '<div class="col-sm-12" id="'.$fri[0]['id'].'friend" style="margin-top:20px; box-sizing:border-box;" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="friendsOnClick(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)">' ;
      echo   '<div class="col-sm-3" id="'.$fri[0]['id'].'friend_picture"><img src="'.$fri[0]['picture'].'" class="img-circle img-responsive" width="60" height="60" style="margin:0px auto;" id="'.$fri[0]['id'].'friend_pic"></div>' ;
      echo   '<div class="col-sm-9" style="font-size:20px;" id="'.$fri[0]['id'].'friend_name">'.$fri[0]['nickname'].'<br>'.$last_log.'</div>' ;
      echo   '<div class="col-sm-12" style="display:none;" id="'.$fri[0]['id'].'friend_eac">'.$friend.'</div>' ;
      echo '</div>' ;
    }
    $conn = mySQL_connection::stop_connected() ;
    */
    ?>
    </div>

  </div>

  
  
</body>

</html>

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
$user_picture = $result[0]['picture'] ;
$my_friend = $result[0]['friends'] ;
$my_eac = getEmailAccount($email) ;

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
  <title>Friends</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' rel='stylesheet'>

  <style>

  </style>

</head>

<body style="background-color:#EDEDED;">


</body>

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
          window.location.replace('sign_in.php') ;
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

        function myFileOnClick() {
          window.location.replace('myFile.php') ;
        }

        function myMessageOnClick() {
          window.location.replace("message_board.php") ;
        }

        function friendsOnClick(x) {
          var id = parseInt(x).toString() ;
          var eac = document.getElementById(id+'friend_eac').innerHTML ;
          window.location.href = "message_board.php?eac="+eac ;
        }

        function resOnMouse(x) {
          document.getElementById(x).style.backgroundColor = "lightgray" ;
        }

        function resOutMouse(x) {
          document.getElementById(x).style.backgroundColor = "snow" ;
        }

        function resDownMouse(x) {
          document.getElementById(x).style.backgroundColor = "gray" ;
        }

        function resUpMouse(x) {
          document.getElementById(x).style.backgroundColor = "snow" ;
        }

        function search_eacOnMouse(x) {
          document.getElementById(x).style.backgroundColor = "#EDEDED" ;
        }

        function search_eacOutMouse(x) {
          document.getElementById(x).style.backgroundColor = "snow" ;
        }

        function search_eacDownMouse(x) {
          document.getElementById(x).style.backgroundColor = "lightskyblue" ;
        }

        function search_eacUpMouse(x) {
          document.getElementById(x).style.backgroundColor = "snow" ;
        }

        function finishEditSearch() {
          if ( event.keyCode == 13 )  {
            document.getElementById('search_eac').click() ;
            document.getElementById('edit_search').contentEditable = false ;
            document.getElementById('edit_search').innerHTML = "搜尋" ;
            document.getElementById('edit_search').style.color = "lightgray" ;
          }
        }

        function search_eacOnClick(x) {
          var search = document.getElementById('edit_search').innerHTML ;
          document.getElementById("searchResult").style.display = "initial" ;
          var htp = new XMLHttpRequest() ;
          var str = '' ;
          htp.onreadystatechange = function() {
            if ( htp.readyState == 4 && htp.status == 200 ) str = htp.responseText ;
          }
          htp.open("GET","msg_reply.php?did=9&eac="+search,false) ;
          htp.send() ;

          if ( str == "no_result" ) document.getElementById("noFitResult").style.display = "initial" ;
          else {
            var offset = 0 ;
            var uid = '' , eac = '' , pic = '' , name = '' ;
            var start = 0 , end = 0 ;
            var kind = 0 ;     //0=find id, 1=find eaccount , 2=find picture , 3=find nickname , 4=output
            do { 
              end = str.indexOf('***',offset) ;
              switch( kind ) {
                case 0 :
                  uid = str.substr(start,end-start) ;
                  kind = 1 ;
                  break ;
                case 1 :
                  eac = str.substr(start,end-start) ;
                  kind = 2 ;
                  break ;
                case 2 :
                  pic = str.substr(start,end-start) ;
                  kind = 3 ;
                  break ;
                case 3 :
                  name = str.substr(start,end-start) ;
                  kind = 4 ;
                  break ;
              }
              start = end+3 ;
              offset = end+1 ;
              if ( kind == 4 ) {
                kind = 0 ;
                document.getElementById('searchResult').innerHTML += '<div class="col-sm-12" id="'+uid+'sere" style="margin-bottom:20px;" onmouseover="resOnMouse(this.id)" onmouseout="resOutMouse(this.id)" onclick="friendsOnClick(this.id)" onmousedown="resDownMouse(this.id)" onmouseup="resUpMouse(this.id)"></div>' ;
                document.getElementById(uid+'sere').innerHTML += '<div class="col-sm-12" style="display:none" id="'+uid+'friend_eac">'+eac+'</div>' ;
                document.getElementById(uid+'sere').innerHTML += '<div class="col-sm-3"><img src="'+pic+'" class="img-circle img-responsive" id="'+uid+'iimg" width="60" height="60" style="margin:0px auto;"></div>' ;
                document.getElementById(uid+'sere').innerHTML += '<div class="col-sm-9" style="font-size:20px; padding=8px;" id="'+uid+'tnick">'+name+'</div>' ;
                document.getElementById(uid+'tnick').style.height = document.getElementById(uid+'iimg').height ; 
                document.getElementById(uid+'tnick').style.paddingTop = (parseInt(document.getElementById(uid+"friend_pic").height)/3) + "px" ; 
              }
            }while ( end != -1 ) ;
          }


        }

        function edit_searchOnClick(x) {
          if ( document.getElementById(x).innerHTML == "搜尋" ) document.getElementById(x).innerHTML = "" ;
          document.getElementById(x).style.color = "black" ;
          document.getElementById(x).contentEditable = true ;
        }

        function edit_searchLostFocus(x) {
          var str = document.getElementById(x).innerHTML ;
          if ( str.length <= 0 ) {
            document.getElementById(x).innerHTML = "搜尋" ;
            document.getElementById(x).style.color = "lightgray" ;
          }
          document.getElementById(x).contentEditable = false ;
        }

      function goodOnClick(x) {
        var id = parseInt(x).toString() ;
        var what = null ;
        var n = null ;
        if ( document.getElementById(x).style.color != "blue" ) {
          document.getElementById(x).style.color = "blue" ;
          what = 1 ;
        }
        else {
          document.getElementById(x).style.color = "gray" ;
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
        
    </script>

    <div class="container=fluid">
        <div class="col-sm-12" style="display:none;" id="nname"><?php echo($user_nickname);?></div>
        <div class="col-sm-12" style="display:none;" id="eaccount"><?php echo($my_eac); ?></div>
        <div class="row" style="height:80px; background-color:royalblue">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h1 style="color:cornsilk" id="nname"><dl><dt><?php echo($user_nickname);?></dt></dl></h1>
        </div>
        <div class="col-sm-1">
            <input type="button" class="btn" id="log_out" name="log_out" value="登出" style="background-color:royalblue; color:white; font-size:32px; height:80px; text-align:center;" onclick="logoutOnClick()">
        </div>
        </div>
        <div class = "col-sm-3" style = "height:max-content; text-align:center;" id="user_interface" name="user_interface">
        <img src=<?php echo($user_picture);?> class="img-circle img-responsive" width="324" height="368" style="margin:0px auto;" id="uimg">
            <br>
            <div style="color:black; font-size:26px;" id="myInformation" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myInformationOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>個人資訊</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myFile" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myMessageOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>留言板</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myPicture" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myPictureOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的照片</dt></dl></div>
            <div style="color:black; font-size:26px;" id="myFriend" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="myFileOnClick()" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)"><dl><dt>我的檔案</dt></dl></div>
        </div>
        <div class="col-sm-6">
        <div class="col-sm-12" style="font-size:24px; font-weight:bold; margin-top:20px; background:snow; padding:6px; border-radius:18px; padding-left:20px;">好友的貼文</div>
        <?php
      
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
        $my_eac = getEmailAccount($email) ;
      
        if ( !empty($my_friends) ) {
          $c = count($my_friends) ;
          $where = "owner='$my_friends[0]'" ;
          for ( $i = 1 ; $i < $c ; $i++ ) {
            $fri = $my_friends[$i] ;
            $where .= " or owner='$fri'" ;
          }
          $result = $conn->get_select('user_posts','*',$where,"post_time desc") ;
        }else $result = null ;
  
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
            /*
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
            */

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
        }else echo '<div class="col-sm-12" style="font-size:32px; font-weight:bold; margin-top:20px; background:snow; padding:12px; border-radius:18px; padding-left:20px;">你沒有朋友</div>' ; ;
      
        $conn = mySQL_connection::stop_connected() ;
      
        ?>
        </div>
        <div class = "col-sm-3" style="height:max-content" id="friend_board" name="friend_board">
          <div class="col-sm-12" style="font-size:24px; font-weight:bold; margin-top:20px; background:snow; padding:6px; border-radius:18px; padding-left:20px;">好友列表</div>

          <div class="col-sm-12" style="font-size:20px; font-weight:bold; margin-top:20px; background:snow; padding:6px; border-radius:18px;"> 
            <div class="col-sm-10" style="outline:none; color:lightgray;" id="edit_search" onkeydown="finishEditSearch()" onclick="edit_searchOnClick(this.id)" onfocusout="edit_searchLostFocus(this.id)">搜尋</div>
            <div class="col-sm-2" id="search_eac" style="text-align:center;" onmouseover="search_eacOnMouse(this.id)" onmouseout="search_eacOutMouse(this.id)" onclick="search_eacOnClick()" onmousedown="search_eacDownMouse(this.id)" onmouseup="search_eacUpMouse(this.id)"><i class="fa fa-search"></i></div>
          </div>

          <div class="col-sm-12" style="height:max-content; background-color:snow; border-radius:18px; margin-top:20px; display:none;" id="searchResult">
            <div class="col-sm-12" style="font-size:18px; color:gray; margin-top:10px; font-weight:bold;">搜尋結果</div>
            <div class="col-sm-12" style="height:1px; background-color:lightgray; margin-top:10px; margin-bottom:20px;"></div>
            <div class="col-sm-12" style="margin-top:10px; margin-bottom:10px; display:none;" id="noFitResult">沒有符合的搜尋結果</div>
            
          </div>

            <?php
            
            $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
            foreach($my_friends as $friend) {
              $fri = $conn->get_select('user_account','id,nickname,picture',"eaccount='$friend'") ;
              echo '<div class="col-sm-12" id="'.$fri[0]['id'].'friend" style="margin-top:20px; box-sizing:border-box;" onmouseover="myButtonOnMouse(this.id)" onmouseout="myButtonOutMouse(this.id)" onclick="friendsOnClick(this.id)" onmousedown="myButtonDownMouse(this.id)" onmouseup="myButtonUpMouse(this.id)">' ;
              echo   '<div class="col-sm-3" id="'.$fri[0]['id'].'friend_picture"><img src="'.$fri[0]['picture'].'" class="img-circle img-responsive" width="60" height="60" style="margin:0px auto;" id="'.$fri[0]['id'].'friend_pic"></div>' ;
              echo   '<div class="col-sm-9" style="font-size:20px;" id="'.$fri[0]['id'].'friend_name">'.$fri[0]['nickname'].'</div>' ;
              echo   '<div class="col-sm-12" style="display:none;" id="'.$fri[0]['id'].'friend_eac">'.$friend.'</div>' ;
              echo '</div>' ;
              echo '<script>document.getElementById("'.$fri[0]['id'].'friend_name").style.height = document.getElementById("'.$fri[0]['id'].'friend_pic").height + "px"</script>' ;
              echo '<script>document.getElementById("'.$fri[0]['id'].'friend_name").style.paddingTop = (parseInt(document.getElementById("'.$fri[0]['id'].'friend_pic").height)/3) + "px"</script>' ;
            }
            $conn = mySQL_connection::stop_connected() ;
            ?>
        </div>
    </div>

</html>
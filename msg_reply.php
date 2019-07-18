<?php
include('get_mySQL.php') ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

date_default_timezone_set("Asia/Taipei"); 
$time = date('Y-n-d G:i') ;
$zero = 0 ;
$nullstring = "" ;

//id=0 insert new post
//id=1 insert new reply
//id=2 update count of good and say_good 
//id=3 update edit post (content) + post_time
//id=4 get all replies of a post
//id=5 get user picture by email account
//id=6 replyer delete a reply
//id=7 post has been delete
//id=8 update the last_login time of a user
//id=9 search a user with email account
if ( isset($_GET['did']) ) $doID = $_GET['did'] ;
else echo "idERROR" ;

switch($doID) {
    //insert new post and return id of this post
    case 0 :
        $owner = $_GET['owner'] ;
        $content = $_GET['content'] ;
        $belong = $_GET['be'] ;
        if ( empty($owner) || empty($content) || empty($belong) ) {
            echo "Context error" ;
            break ;
        }
        $db = "user_posts" ;
        $fields = "(owner,board,post_time,content,reply,replies,good,say_good)" ;
        $values = "('$owner','$belong','$time','$content','$zero','$nullstring','$zero','pre,')" ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
        $result = $conn->insert_to($db,$fields,$values) ;
        $result = $conn->get_select($db,'id',"content='$content'") ;
        $conn = mySQL_connection::stop_connected();
        echo $result[0]['id'] ;
        break ;

    //insert new reply and return the count id of this reply
    case 1 :
        $pid = $_GET['pid'] ;
        $eac = $_GET['eac'] ;
        $content = $_GET['cont'] ;
        if ( empty($eac) || empty($pid) || empty($content) ) { echo "Context error" ; break ; }
        $cont = '' ;
        for ( $i = 0 ; $i < strlen($content) ; $i++ ) if ( $content[$i] != '*' ) $cont .= $content[$i] ;

        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
        $result = $conn->get_select('user_posts','replies,reply',"id='$pid'") ;
        $Reply = $result[0]['replies'] ;
        $count = $result[0]['reply'] ;
        $Reply .= $eac . '*' . $cont . '*' . $time . '*' ;
        $count++ ;
        $result = $conn->update_data('user_posts',"reply='$count',replies='$Reply'","id='$pid'") ;
        $conn = mySQL_connection::stop_connected() ;
        echo $count ;
        break ;
    
    //update count of good, say_good
    case 2 :
        $pid = $_GET['pid'] ;
        $who = $_GET['eaccount'] . ',' ;
        $what = $_GET['gdy'] ;
        $db = "user_posts" ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
        $result = $conn->get_select($db,'good,say_good',"id='$pid'") ;
        $count = (int) $result[0]['good'] ;
        $people = (string) $result[0]['say_good'] ;

        //user take back good
        if ( $what == 0 ) {
            $count-- ;
            $people = str_replace($who,'',$people) ;
        //user give a good    
        }else {
            $count++ ;
            $people .= $who ;
        }

        $result = $conn->update_data($db,"good='$count',say_good='$people'","id='$pid'") ;
        $conn = mySQL_connection::stop_connected() ;
        echo $count ;
        break ;

    //update post content and post_time , return success(edit time) or not
    case 3 :
        $pid = $_GET['pid'] ;
        $cont = $_GET['cont'] ;
        $ts = $time . ' 已編輯' ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
        $result = $conn->update_data('user_posts',"post_time='$ts',content='$cont'","id='$pid'") ;
        $conn = mySQL_connection::stop_connected() ;
        if ( $result ) echo $ts ;
        else echo "error" ;
        break ;

    //select replies of a post and return
    case 4 :
        $pid = $_GET['pid'] ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ; 
        $result = $conn->get_select('user_posts','replies',"id='$pid'") ;
        $conn = mySQL_connection::stop_connected() ;
        echo $result[0]['replies'] ;
        break ;

    //find a user pic by email account
    case 5 :
        $eac = $_GET['eac'] ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ; 
        $result = $conn->get_select('user_account','picture,nickname',"eaccount='$eac'") ;
        $conn = mySQL_connection::stop_connected() ;
        echo $result[0]['picture']." ".$result[0]['nickname'] ;
        break ;

    //when a user delete a reply
    case 6 :
        $pid = $_GET['pid'] ;
        $content = $_GET['cont'] ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ; 
        $result = $conn->get_select('user_posts','reply,replies',"id='$pid'") ;
        $count = $result[0]['reply'] ;
        $string = $result[0]['replies'] ;
        $count-- ;
        $string = str_replace($content,"",$string) ;
        $result = $conn->update_data('user_posts',"reply='$count',replies='$string'","id='$pid'") ;
        $conn = mySQL_connection::stop_connected() ;
        echo $count ;
        break ;

    //when a post has been delete
    case 7 :
        $pid = $_GET['pid'] ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ; 
        $result = $conn->delete_data('user_posts',"id='$pid'") ;
        $conn = mySQL_connection::stop_connected() ;
        if ( $result ) echo "success" ;
        else echo "error" ;
        break ;

    //update last_login time, return success or not ;
    case 8 :
        $eaccount = $_GET['eac'] ;
        $t = $_GET['t'] ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ; 
        $result = $conn->update_data('user_account',"last_login='$t'","eaccount='$eaccount'") ;
        $conn = mySQL_connection::stop_connected() ;
        if ( $result ) echo "success" ;
        else echo "error" ;
        break ;

    //search a user by email account , return picture and nickname
    case 9 : 
        $eac = $_GET['eac'] ;
        $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ; 
        $result = $conn->get_select('user_account',"id,picture,nickname,eaccount","eaccount='$eac' or nickname='$eac'") ;
        $conn = mySQL_connection::stop_connected() ;
        if ( $result ) {
            $search = '' ;
            foreach ( $result as $res ) {
                $search .= $res['id'] . '***' . $res['eaccount'] . '***' . $res['picture'] . '***' . $res['nickname'] . '***' ;
            }
            echo $search ;
        }else echo "no_result" ;
        break ;

    default : 
        echo "no_result" ;
        break ;
}

?>
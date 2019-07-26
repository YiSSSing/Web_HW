<!DOCTYPE html>
<html lang="en">
<head>
  <title>文章列表</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>


<?php 
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

$conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
$user_posts = $conn->get_select("user_posts","id,owner,content,post_time") ;
$conn = mySQL_connection::stop_connected();

?>

<script>
    function postOnClick(pid) {
        if ( pid < 0 ) setcookie("eac",document.getElementById(pid).innerHTML) ;
        else {
            oid = pid * (-1) ;
            setcookie("eac",document.getElementById(oid).innerHTML) ;
        }
        window.location.replace('message_board.php') ;
    }
</script>

<body>
    <div class = "container-fluid">
        <h1 style="text-align:center;">User Posts</h1>
        <div class = "row" >
            <div class = "col-sm-1"></div>
            <div class= "col-sm-10"></div>
            <table class ="table table-striped">
                <thead>
                    <tr>
                        <th>Owner</th>
                        <th>Content</th>
                        <th>Post time</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach( $user_posts as $posts ) {
                            echo "<tr>" ;
                            echo "<td>" . $posts['owner'] . "</td>" ;
                            echo "<td>" . $posts['content'] . "</td>" ;
                            echo "<td>" . $posts['post_time'] . "</td>" ;
                            echo '<td><input type="button" id='.$posts['id'].' class="btn btn-primary" value="visit" onclick="postOnClick(this.id)">' ;
                            echo "</tr>" ;
                        }
                    ?>
                </tbody>
            </table>
            <div class="col-sm-1"></div>
        </div>
    </div>
<body>
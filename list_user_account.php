<!DOCTYPE html>
<html lang="en">
<head>
  <title>User_List</title>
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
$user_accounts = $conn->get_select("user_account") ;
$conn = mySQL_connection::stop_connected();


?>

<body>
    <div class = "container-fluid">
        <h1 style="text-align:center;">User Accounts</h1>
        <div class = "row" >
            <div class = "col-sm-1"></div>
            <div class= "col-sm-10"></div>
            <table class ="table table-striped">
                <thead>
                    <tr>
                        <th>Email (account)</th>
                        <th>Password</th>
                        <th>Nick Name</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach( $user_accounts as $user ) {
                            echo "<tr>" ;
                            echo "<td>" . $user['email'] . "</td>" ;
                            echo "<td>" . $user['password'] . "</td>" ;
                            echo "<td>" . $user['nickname'] . "</td>" ;
                            echo "<td>" . $user['first_name'] . "</td>" ;
                            echo "<td>" . $user['last_name'] . "</td>" ;
                            echo "<td>" . $user['gender'] . "</td>" ;
                            echo "</tr>" ;
                        }
                    ?>
                </tbody>
            </table>
            <div class="col-sm-1"></div>
        </div>
    </div>
<body>
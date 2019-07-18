<?php session_start() ; ?>

<?php
$log = " " ;
if ( isset($_COOKIE['loging_out']) ) {
    unset($_SESSION['keep_name']) ;
    unset($_SESSION['keep_password']) ;
    unset($_SESSION['keep_key']) ;
    setcookie("ck_account") ;
    setcookie("ck_password") ;
    setcookie('loging_out') ;
    $log = "登出成功" ;
} 

if ( isset($_SESSION['keep_name']) && isset($_SESSION['keep_password']) && isset($_SESSION['keep_key']) ) {
    setcookie("ck_account",$_SESSION['keep_name']) ;
    setcookie("ck_password",$_SESSION['keep_password']) ;
    //compare data to database here
    header("Location: message_board.php") ;
}else {
    if ( isset($_SESSION['keep_name']) ) unset($_SESSION['keep_name']) ;
    if ( isset($_SESSION['keep_password']) ) unset($_SESSION['keep_password']) ;
    if ( isset($_SESSION['keep_key']) ) unset($_SESSION['keep_key']) ;
}

if ( isset($_COOKIE['signUpSuccess']) ) {
    $log = "註冊成功，請登入" ;
    setcookie('signUpSuccess') ;
}

?>

<?php 
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;
$height = "90px" ;

$color_password = "lightgray" ;
$color_email = "lightgray" ;
$double_login = " " ;
$incorrect_password = " " ;
$is_inputCorrect = 0 ;

if ( isset($_POST['email'])) { 
    $email = $_POST['email'] ;
    $password = $_POST['password'] ;
    $keep_login = ( isset($_POST['remenber']) ? true : false ) ;

    $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
    $fields = "email,password" ;
    $db = "user_account" ;
    $where = "email = '$email'" ;
    $result = $conn->get_select($db,$fields,$where) ;
    if ( isset($result) ) { 
        $user = $result[0]['email'] ;
        $pass = $result[0]['password'] ;
    }else $user = $pass = null ;

    if ( $email != $user )  {
        $double_login = "The account is incorrect or not exist" ;
        $color_email = "red" ;
        $height = "70px" ;
    }else $is_inputCorrect += 1 ;

    if ( $is_inputCorrect >= 1 ) {
        if ( $password != $pass ) {
            $incorrect_password = "Password Incorrect" ;
            $color_password = "red" ;
            $height = "65px" ;
        }else $is_inputCorrect += 1 ;
    }

}


if ( $is_inputCorrect >= 2 ) {  

    setcookie("ck_account",$email) ;
    setcookie("ck_password",$password) ;

    if ( $email == 'NULL@gmail.com' && $password == 'administrator' ) {
        header("Location: list_user_account.php") ;
        die() ;
    }

    if ( $keep_login ) {
        if ( isset($_SESSION['keep_name']) && $_SESSION['keep_name'] == $email ) {
        }else {
            //when a user using another account to log in 
            //the corresponding session will be replaced
            $_SESSION['keep_name'] = $email ;
            $_SESSION['keep_password'] = $password ;
            $_SESSION['keep_key'] = rand_string(20) ;
            //store these information to database here
        }
    }

    header("Location: message_board.php") ;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Log in</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

    <script>
        function SignIn() {
            if ( event.keyCode == 13 ) {
                var form = document.getElementById('logForm') ;
                form.submit() ;
            }
        }
    </script>

    <body style="background-color:slategrey;">
        <h2 style="text-align:center"><dl><dt><?php echo($log); ?></dt></dl></h2>
        <div class="container-fluid" >
            <div class="row">
                <div class="col-sm-3" style="background-color:slategrey;"></div>
                <div class="col-sm-6" style="background-color:ivory; text-align:center; height:450px; margin-top:200px; border-radius:20px">
                    <h1><dl>Log in</dl></h1>
                    <br>
                    <form class="form-horizontal" id="logForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <p class="text-danger" style="color:red;"><?php echo($double_login) ?></p>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" required name="email" placeholder="Email" maxlength="60" style="border-color:<?php echo($color_email)?>">
                            </div>
                        </div>
                        <p class="text-danger" style="color:red;"><?php echo($incorrect_password) ?></p>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" required name="password" placeholder="Password" maxlength="20" minlength="8" style="border-color:<?php echo($color_password)?>" onkeydown="SignIn()">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group" style="margin-top:<?php echo($height);?>;">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8 checkbox">
                                <label><input type="checkbox" name="remenber" id="remenber" value="true">Remenber me</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary btn-lg" name="submit" id="submit">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-3" style="background-color:slategrey;"></div>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6" style="text-align:center; color:snow;"><dl><dt>No account? <a href='Login_partice.php'><abbr>Sign Up</abbr></a></dt></dl></div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </body>
    

</html>
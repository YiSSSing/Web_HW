<?php 
include("get_mySQL.php") ;
$HOST_name = gethostbyname(gethostname()) ;
$local_user = "root" ;
$local_password = "Yisinglabuse" ;
$local_db = "php_partice" ;

$color_email = "lightgray" ;
$double_login = " " ;
$is_inputCorrect = false ;
$height = "70px" ;

if ( isset($_POST['first_name'])) { 
    $fname = $_POST['first_name']; 
    $lname = $_POST['last_name'] ;
    $gender = $_POST['gender'] ;
    $email = $_POST['email'] ;
    $password = $_POST['password'] ;
    $password_check = $_POST['password_check'] ;
    $comment = $_POST['comment'] ;

    $conn = mySQL_connection::get_connected($HOST_name,$local_user,$local_password,$local_db) ;
    $fields = "email" ;
    $db = "user_account" ;
    $where = "email = '$email'" ;
    $result = $conn->get_select($db,$fields,$where) ;
    $conn = mySQL_connection::stop_connected();
    if ( !empty($result) )  {
        $double_login = "The email address is already login by other user" ;
        $color_email = "red" ;
        $height = "40px" ;
        $is_inputCorrect = false ;
    }else $is_inputCorrect = true ;

}


if ( $is_inputCorrect ) {
    setcookie("first_name",$fname,time()+30) ;
    setcookie("last_name",$lname,time()+30) ;
    setcookie("gender",$gender,time()+30) ;
    setcookie("email",$email,time()+30) ;
    setcookie("password",$password,time()+30);
    setcookie("comment",$comment,time()+30) ;

    header("Location: login_process.php") ;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sign up</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

    <body style="background-color:slategray;">

        <script type="text/javascript">
            function ErrorConfirmPassword() {
                $a = document.getElementById("password").value ;
                $b = document.getElementById("password_check").value ;
                if ( $a != $b ) {
                    document.getElementById("password_check").style.borderColor = "red" ;
                    document.getElementById("checkbox_margin").style.marginTop = "40px" ;
                    document.getElementById("error_confirm").innerHTML = "Confirm password is different" ;
                    document.getElementById("error_confirm").style.color = "red" ;
                }else {
                    document.getElementById("password_check").style.borderColor = "lightgray" ;
                    document.getElementById("checkbox_margin").style.marginTop = "70px" ;
                    document.getElementById("error_confirm").innerHTML = " " ;
                }
            }       
        </script>

        <div class="container-fluid" >
            <div class="row">
                <div class="col-sm-3" style="background-color:slategrey;"></div>
                <div class="col-sm-6" style="background-color:ivory; text-align:center; height:700px; margin-top:100px; border-radius:20px">
                    <h1><dl>Register</dl></h1>
                    <p class="text-muted">Create your account. It's free and only take a minute.</p>
                    <br>
                    <style>
                        select:invalid { color: gray; }
                    </style>
                    <form class="form-horizontal"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="first_name" required name="first_name" placeholder="First Name" maxlength="20" minlength="1">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="last_name" required name="last_name" maxlength="20" minlength="1" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label for="gender"></label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled selected hidden>Choose gender...</option>
                                    <option value="male">male</option>
                                    <option value="female">female</option>
                                    <option value="none">none of above</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-danger" style="color:red;"><?php echo($double_login) ?></p>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" required name="email" placeholder="Email" maxlength="60" style="border-color:<?php echo($color_email)?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" required name="password" placeholder="Password" maxlength="20" minlength="8">
                            </div>
                        </div>
                        <p id="error_confirm"> </p>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password_check" required name="password_check" placeholder="Confirm Password" maxlength="20" onchange="ErrorConfirmPassword()">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="5" id="comment" name="comment" placeholder="Self-intro" style="resize:none;" maxlength="200"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="checkbox_margin" style="margin-top:<?php echo($height);?>">
                            <div class="col" >
                                <div class="checkbox">
                                    <label><input type="checkbox" name="accept_policy" id="accept_policy" required >I accept the <a href='#'>Terms of Use</a> & <a href='#'>Privacy Policy</a>.</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary btn-lg" name="submit" id="submit">Register Now</button>
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
                <div class="col-sm-6" style="text-align:center; color:snow;"><dl><dt>Already have an account? <a href='sign_in.php'><abbr>Sign in</abbr></a></dt></dl></div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </body>
    

</html>


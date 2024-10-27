<?php
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header('Location: user-forgot-pass.php');
    exit();
}

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    $_SESSION['info'] = "Please verify your code first.";
    header('Location: user-reset-pass.php');
    exit();
}

$email = $_SESSION['email'];
$username = $email = $newPass = $conPass = $newPass_err = $conPass_err = "";
$salt = "b2a6ec273b45b1d0ab06130cb4e2e62d";

if(isset($_POST['change-password'])){
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $cpassword = mysqli_real_escape_string($link, $_POST['cpassword']);
    
    $newPass = trim($_POST["password"]);
    if (empty($newPass)) {
        $newPass_err = "Please enter valid password.";
    } elseif (strlen($newPass) < 8) {
        $newPass_err = "Password must have a minimum of 8 characters";
    } elseif (!preg_match("/(?=.*\d)/", $newPass)) {
        $newPass_err = "Password must contain at least one digit";
    } elseif (!filter_var($newPass, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*\d)[a-zA-Z0-9\S]+$/")))) {
        $newPass_err = "Please enter valid password.";
    }

    $conPass = trim($_POST["cpassword"]);
    if (empty($conPass)) {
        $conPass_err = "Please confirm password.";
    } elseif ($conPass !== $newPass) {
        $conPass_err = "Passwords do not match!";
    }

    if(empty($newPass_err) && empty($conPass_err)){
        function logs($link, $email, $user_details)
          {
            date_default_timezone_set('Asia/Manila');
            $date = date('F d, Y h:i:sA');
          
              $format = $date . "\t" . $email . "\t" . $user_details . "\n";
          file_put_contents("activity_history.log", $format, FILE_APPEND);
          }
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $hashedNewPass = md5($salt . $password);
        $update_pass = "UPDATE homeowners SET code = $code, password = '$hashedNewPass' WHERE email = '$email'";
        $run_query = mysqli_query($link, $update_pass);
        if($run_query){
            $info = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            $log_details = "Reset a Password";
            logs($link, $email, $log_details);
            unset($_SESSION['otp_verified']);
            header('Location: user-login-now.php');
            exit();
        }else{
            $password_err= "Failed to change your password!";
        }
    }
    mysqli_close($link);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/user-change-pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Setting New Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
          <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <style>

    #is-invalid {
        border: 2px solid red;
    }

    #fieldDivs {
        margin-top: -20px;
        padding-left: 5px;
        position: absolute;
    }
    
    #fieldErrs {
       color: red;
        font-size: 12px;
    }

    #passwordConditions{
        position: absolute;
        margin-top: -15px;
        text-align: left;
        padding-left: 5px;
    }

    .valid-condition {
        color: green;
        font-size: 12px;
    }

    .invalid-condition {
        color: gray;
        font-size: 12px;
    }

    </style>

</head>
<body class="body">
<section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left">
        <div class="mb-container text-center text-md-left">
            <div class="wrapper mx-auto">
                <div class="logo">
                    <img src="logo.png" alt="">
                </div>
                <div class="text-center mt-3 name">
           Set New Password
            </div>
            <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
            <form method="POST" autocomplete="off">
                    <div class="form-group">
                        <div class="password-container" style="position: relative;">
                        <input class="form-control" id="userPass" type="password" name="password" placeholder="Create new password" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    </div>
                    <div class="form-group">
                        <div class="password-container" style="position: relative;">
                        <input class="form-control" id="conPass" type="password" name="cpassword" placeholder="Confirm your password" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                        <i class="fas fa-eye" id="toggleConfirmPassword" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <div id="passwordConditions"> <span id="fieldErrs"><?php echo $conPass_err;?></span> </div>
                    <br>
                    <div id="passwordConditions"><span id="fieldErrs"><?php echo $newPass_err;?></span></div>
                    </div>
                    <div class="form-group">
                        <input class="btn mt-3" type="submit" name="change-password" value="Change">
                    </div>
                </form>
                <i class="mobile-view"></i>
            </div>
        </div>
    </div>
</section>

<script>
var passwordInput = document.getElementById("userPass");
var passwordConditions = document.getElementById("passwordConditions");

passwordInput.addEventListener("input", updatePasswordConditions);

function updatePasswordConditions() {
  var password = passwordInput.value;
  var conditions = [];

  if (password.length < 8) {
    conditions.push("<span class='invalid-condition'>Password must be at least 8 characters</span>");
  } else {
    conditions.push("<span class='valid-condition'>Password must be at least 8 characters</span>");
  }

  if (!/\d/.test(password)) {
    conditions.push("<span class='invalid-condition'>Password must contain at least one digit</span>");
  } else {
    conditions.push("<span class='valid-condition'>Password must contain at least one digit</span>");
  }

  passwordConditions.innerHTML = conditions.join("<br>");
}
</script>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#userPass');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>

<script>
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirm_password = document.querySelector('#conPass');

    toggleConfirmPassword.addEventListener('click', function (e) {
        const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
        confirm_password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>
<?php
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header('Location: user-forgot-pass.php');
    exit();
}

$email = $_SESSION['email'];
$username = $email = $otp_err = '';
$salt = "b2a6ec273b45b1d0ab06130cb4e2e62d";


if(isset($_POST['check-reset-otp'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($link, $_POST['otp']);
    $check_code = "SELECT * FROM homeowners WHERE code = $otp_code";
    $code_res = mysqli_query($link, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $_SESSION['otp_verified'] = true;
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        $_SESSION['email'] = $email;
        header('location: user-change-pass.php');
        exit();
    }else{
        $otp_err = "You've entered incorrect code!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/user-reset-pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Password Reset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
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
           Code Verification
            </div>

            <form method="POST" autocomplete="off">
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem; font-size: 0.85rem;">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <div>
                        <input class="form-control" type="number" name="otp" placeholder="Enter code" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $otp_err;?></span></div>
                    </div>
                    <div class="form-group">
                        <input class="btn mt-3" type="submit" name="check-reset-otp" value="Submit">
                    </div>
                </form>
                <i class="mobile-view"></i>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>
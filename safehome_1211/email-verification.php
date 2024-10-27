<?php
include "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();

$verification_error = ""; 

if (isset($_POST['verify'])) {
    $_SESSION['success-info'] = "";

    if (isset($_POST["email"]) && isset($_POST["verification_code"])) {
        $email = $_POST["email"];
        $verification_code = $_POST["verification_code"];
    } else {
    
        $verification_error = "Session variables not set";
    }

    $link = mysqli_connect("localhost", "root", "", "demo1");
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $sql = mysqli_query($link, "SELECT * FROM homeowners WHERE email = '$email' AND verification_code = '$verification_code'");
    function logs($link, $username, $details)
    {
      date_default_timezone_set('Asia/Manila');
      $date = date('m-d-Y h:i:sA');
    
        $format = $date . "\t" . $username . "\t" . $details . "\n";
    file_put_contents("activity_history.log", $format, FILE_APPEND);
    }
    if (mysqli_num_rows($sql) == 0){
        $verification_error = "Invalid verification code or unmatched email address."; 
    } else {
        $log_details = "Email Verification Confirmed";
                logs($link, $username, $log_details);
        header("Location: login.php");
        exit();
    }
}




    
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body class="body">
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left">
        <div class="mb-container text-center text-md-left">
            <div class="wrapper mx-auto">
                <div class="logo">
                    <img src="logo.png" alt="">
                </div>
        <div class="text-center mt-4 name">
            Email Verification
        </div>
        <?php if ($verification_error !== ""): ?>
                    <div class="error-message"><p style="color:red;"><?= $verification_error ?></p></div>
                <?php endif; ?>
        <form class="p-3 mt-3" method="post">
        <?php
                   
                    if (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
                        $username = $_SESSION["username"];
                        $password = $_SESSION["password"];
                    }

                    if (isset($_POST["email"]) && isset($_POST["verification_code"])) {
                        $email = $_POST["email"];
                        $verification_code = $_POST["verification_code"];
                    }
                    ?>
                <div class="d-flex align-items-center">
                <span class="far fa-user"></span>
                <input type="email" name="email" id="email" placeholder="Email" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
            </div>
            <div class="d-flex align-items-center">
                <span class="fas fa-key"></span>
                <input type="text" name="verification_code" id="vc" placeholder="Enter verification code" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
            </div>
           
            <div class="row">
                <div class="col-sm-12">
                    <button name="verify" class="btn mt-3"><p class="p">Verify</p></button>
                </div>
            </div>
        </form>
        
    </div>

</section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
  </body>
</html>
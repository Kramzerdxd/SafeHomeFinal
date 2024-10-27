<?php 
include "config.php";
session_start();
$_SESSION['username'];
$_SESSION['password'];

if (!isset($_SESSION['username'])) {
	header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SafeHome Device Setup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="assets/css/user-device-setup.css" rel="stylesheet">

  <style>
  .card-body-1 i,
  .card-body-2 i,
  .card-body-3 i,
  .card-body-4 i {
    color: #C04C43;
    font-size: 28px;
  }

  .card-body-4 {
    padding:73px;
  }

  @media screen and (max-width: 576px) {
    .card .row {
      flex-direction: column;
      align-items: center;
    }

    .card .row .col-6 {
      width: 100%;
      margin-bottom: 15px;
    }

    .card br {
      display: none;
    }


     .card-body-1 .row:nth-child(odd) .col-6:first-child,
    .card-body-1 .row:nth-child(even) .col-6:last-child,
    .card-body-2 .row:nth-child(even) .col-6:first-child,
    .card-body-2 .row:nth-child(odd) .col-6:last-child,
    .card-body-3 .row:nth-child(even) .col-6:first-child,
    .card-body-3 .row:nth-child(odd) .col-6:last-child {
      order: -1;
    }
    
    .card-body-4 {
        padding:3rem;
    }

}
</style>

</head>
<?php
  require_once 'header.php';
  ?> 
  

<body>
    
<section class="py-6 bg-light-primary">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-xl-6 col-lg-8 col-sm-10">
                <h2 class="font-weight-bold" style="color: #444444;">How to set up the device?</h2>
                <p class="text-muted mb-0">Configuring the device is a simple procedure that comprises a few uncomplicated steps:</p>
            </div>
        </div>

        <div class="row text-center justify-content-center px-xl-6 aos-init aos-animate" data-aos="fade-up">
            <div class="col-md-6 my-3">
                <div class="card border-hover-primary hover-scale">
                <div class="card-body-1" style="padding:40px;">
                    <i class="bi bi-1-circle-fill " style="font-size: 55px;"></i>
                        <h5 class="font-weight-bold mb-3">Signup Up for Account</h5><br>
                        <div class="row">
                            <div class="col-6">
                                <img src="assets/img/device-setup-signup.png" class="img-fluid" style="width: 85%; height: auto;">
                            </div>
                            <div class="col-6"><br>
                                <p class="text-muted mb-0">Create an account if you don't have one yet. During signup, provide the required information. You'll receive an email with a verification code to complete the process.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6"><br><br>
                                <p class="text-muted mb-0">Later, another email with your User ID will be sent for setting up the device's WiFi connection.</p>
                            </div>
                            <div class="col-6">
                                <img src="assets/img/device-setup-email.png" class="img-fluid" style="width: 70%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 my-3">
                <div class="card border-hover-primary hover-scale">
                <div class="card-body-2" style="padding:40px;">
                    <i class="bi bi-2-circle-fill" style="font-size: 55px;"></i>
                        <h5 class="font-weight-bold mb-3">Power On the Device</h5>
                        <div class="row">
                            <div class="col-6">
                                <img src="assets/img/device-setup-plugin.png" class="img-fluid" style="width: 75%; height: auto;">
                            </div>
                            <div class="col-6"><br><br><br>
                            <p class="text-muted mb-0">After the signup is complete, power on the device by plugging it directly into an electrical outlet.</p><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6"><br><br><br>
                            <p class="text-muted mb-0">Once it starts up, the device will automatically turn on the red LED.</p><br>
                            </div>
                            <div class="col-6">
                                <img src="assets/img/device-setup-on.png" class="img-fluid" style="width: 90%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 my-3">
                <div class="card border-hover-primary hover-scale">
                <div class="card-body-3" style="padding:40px;">
                    <i class="bi bi-3-circle-fill" style="font-size: 55px;"></i>
                        <h5 class="font-weight-bold mb-3">Setup WiFi Connectivity</h5>
                        <div class="row">
                            <div class="col-6">
                                <img src="assets/img/device-setup-wifi1.png" class="img-fluid" style="width: 90%; height: auto;">
                            </div>
                            <div class="col-6"><br>
                            <p class="text-muted mb-0">On your smartphone or computer, configure your WiFi settings and make sure to click SafeHome's WiFi for connectivity and then manage the router.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6"><br><br>
                            <p class="text-muted mb-0">Connect to the device's Access Point, and input your WiFi's SSID, WiFi Password, and the User ID from the signup email into the device's captive portal.</p>
                            </div>
                            <div class="col-6">
                                <img src="assets/img/device-setup-wifi2.png" class="img-fluid" style="width: 90%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 my-3">
                <div class="card border-hover-primary hover-scale">
                    <div class="card-body-4">
                    <i class="bi bi-4-circle-fill" style="font-size: 55px;"></i>
                        <h5 class="font-weight-bold mb-3">Login</h5>
                        <div class="row">
                            <div class="col-6"><br><br><br>
                                <img src="assets/img/device-setup-login1.png" class="img-fluid" style="width: 100%; height: auto;">
                                <br><br><br><br><br>
                            </div>
                            <div class="col-6"><br><br><br><br>
                            <p class="text-muted mb-0">After successfully connecting the device to the internet, you can log in to the website. There, you will have access to charts and sensor logs that contain the data transmitted by the device.</p>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="section-title"><br>
                <a href="user-home.php" class="btn-back-home scrollto"><i class="bi bi-arrow-left-circle-fill"></i> Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</body>

</html>
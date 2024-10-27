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

  <title>Read more about Safehome</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="assets/css/user-read-more.css" rel="stylesheet">
</head>
<?php
  require_once 'header.php';
  ?> 

<body>
    
    <section class="py-6 bg-light-primary">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-xl-6 col-lg-8 col-sm-10">
                <h2 class="font-weight-bold" style="color: #444444;">What we offer?</h2>
                <p class="text-muted mb-0">Safehome is your companion for emergency situations. Making it possible to deliver the best services and ensure the quality of safeness inside the household.</p>
            </div>
        </div>

        <div class="row row-cols-lg-3 row-cols-md-2 row-cols-1 text-center justify-content-center px-xl-6 aos-init aos-animate" data-aos="fade-up">
            <div class="col my-3">
                <div class="card border-hover-primary hover-scale">
                    <div class="card-body">
                        <img src = "assets/img/safety.png" alt="" style="max-height:70px; margin-bottom:15px; margin-top:10px">
                        <h5 class="font-weight-bold mb-3">Safety</h5>
                        <p class="text-muted mb-0">Safety is our top priority. Emergencies can possibly happen inside our houses in any various form. May it be accidentally or natural phenomenon.</p>
                    </div>
                </div>
            </div>
            <div class="col my-3">
                <div class="card">
                    <div class="card-body">
                    <img src = "assets/img/security.png" alt="" style="max-height:70px; margin-bottom:15px; margin-top:10px">
                        <h5 class="font-weight-bold mb-3">Security</h5>
                        <p class="text-muted mb-0">User's security are safe with our hands. Reports and logs are kept by trusted admins capable in inceasing the assurance of securing data.</p><br>
                    </div>
                </div>
            </div>
            <div class="col my-3">
                <div class="card">
                    <div class="card-body">
                        <img src = "assets/img/wifi-connect.png" alt="" style="max-height:77px; margin-bottom:15px; margin-top:10px">
                        <h5 class="font-weight-bold mb-3">Internet Connectivity</h5>
                        <p class="text-muted mb-0">Safehome delivers a convenient way connecting to your devices, making it easier for easy tracking of suspected emergency.<p>
                    </div>
                </div>
            </div>
            <div class="col my-3">
                <div class="card">
                    <div class="card-body">
                    <img src = "assets/img/emer-sensors.png" alt="" style="max-height:70px; margin-bottom:15px; margin-top:10px">
                        <h5 class="font-weight-bold mb-3">Emergency Sensors</h5>
                        <p class="text-muted mb-0">Essential sensors are integrated to fully maximize the effort of detecting emergencies. Sensors elevate the purpose of Safehome as it initializes the emergency levels.</p>
                    </div>
                </div>
            </div>
            <div class="col my-3">
                <div class="card">
                    <div class="card-body">
                    <img src = "assets/img/alert-notif.png" alt="" style="max-height:70px; margin-bottom:15px; margin-top:10px">
                        <h5 class="font-weight-bold mb-3">Alert Notification</h5>
                        <p class="text-muted mb-0">Notifying homeowners, LGUs and BFP based on emergency level the Safehome detected. Significantly enhancing the overall capability of the device.</p>
                    </div>
                </div>
            </div>
            <div class="col my-3">
            </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title"><br>
                        <a href="user-home.php" class="btn-back-home scrollto"><i class="bi bi-arrow-left-circle-fill"></i> Back to Home</a>
                    </div>
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
<?php
include "config.php";


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

  <title>Safehome</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>

<body>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

 
  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">


  <link href="assets/css/header.css" rel="stylesheet">
</head>

<body>

<script>
       
        function logout() {
            Swal.fire({
                text: 'Are you sure you want to log out?',
                icon: 'warning',
                iconColor: '#C04C43',
                background: '#f2f6fc',
                showCancelButton: true,
                confirmButtonText: 'Logout',
                confirmButtonColor: '#C04C43',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>

    


  
  <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center">
        <div class="logo me-auto">
        <a href="user-home.php"><img src="assets/img/logo1.png" alt="" class="img-fluid"></a></h1>
      </div>
            <nav id="navbar" class="navbar order-last order-lg-0">
            <?php 
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $selectUser = "SELECT * FROM homeowners where username='$username' and password='$password'";
        $query = mysqli_query($link, $selectUser);
        while($info = mysqli_fetch_assoc($query)){
            $id = $info['id'];
            $firstname = $info['firstname'];
            $lastname = $info['lastname'];
            $email = $info['email'];
            $address = $info['address'];
            $contact = $info['contact'];
            $picture = $info['picture'];
        ?>
                <ul>
                    <li><a class="nav-link scrollto" href="user-home.php">Home</a></li>
                    <li><a class="nav-link scrollto" href="user-about.php">About</a></li>
                    <li class="dropdown"><a href="">
                            <?php
                            if ($picture == '') {
                                echo '<img class="img-account-profile" style= "border-radius:50%; width: 35px; height: 35px; object-fit: cover; object-position: 25% 25%; margin-right:7px; margin-top:1px;"" src="assets/img/default-avatar.png">';
                                echo ucwords($firstname) . " " . ucwords($lastname);
                            } else {
                                echo '<img class="img-account-profile" style="border-radius:50%; width: 35px; height: 35px; object-fit: cover; object-position: 25% 25%; margin-right:7px; margin-top:1px;" src="assets/uploaded-img/' . $picture . '">';
                                echo  ucwords($firstname) . " " . ucwords($lastname);
                            }
                            ?>
                            <i class="bi bi-caret-down-fill"></i></a>
                        <ul>
                            <li><a href="user-view-profile.php">Profile</a></li>
                            <li><a href="sensorLogs.php">Sensor Logs</a></li>
                            <li><a href="logHistory.php" style="border-bottom: 1px solid rgba(33, 40, 50, 0.125);">Activity History</a></li>
                            <li><a href="#" onclick="logout()">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
                <?php
        }
        ?>
            </nav>
        </div>
    </header>


                
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
  
    <script src="assets/js/main.js"></script>

  
  </body>
  
  </html>
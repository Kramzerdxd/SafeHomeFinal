<?php 
include "config.php";
session_start();

$_SESSION['username'];
$_SESSION['password'];
if (!isset($_SESSION['username'])) {
	header("location: login.php");
} 

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];


if (isset($_POST['update_threshold'])) {

  $sensorsensitivy = $_POST['threshold_mode'];
  $sql = "UPDATE homeowners SET threshold_mode = '$sensorsensitivy' WHERE username='$username' AND password='$password'"; // Assuming the ID is 1, adjust it based on your table structure

  if($statement = mysqli_query ($link, $sql)){
      function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("activity_history.log", $format, FILE_APPEND);
     }
        $log_details = "Changed Sensors Sensitivity";
        logs($link, $username, $log_details);
        header("Location: user-home.php");
        exit();
     } else{
      echo "Oops! Something went wrong. Please try again later.";
     }
    mysqli_stmt_close($stmt);
}


?>


<!doctype html>
<html lang="en">
<head>

    
    <title>Home</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/user-home.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>

  #hero {
    height: auto;
  }

  .main-charts{
    position: relative;
    width: 100%;
  }

  .main-precautions {
    position: relative;
  }

  .wrapper {
    width: 100%; 
    height: auto; 
    max-width: 1500px; 
    padding: 10px;
    position: relative;
  }

  #gas{
    width: 660px; 
    height: 380px;
    padding: 20px 30px 55px 30px;
    background-color:  #F1EDEE;
    border-radius: 13px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    float:left;
    position: absolute; 
    top:12px; 
    left: 50px; 
    z-index: 2;
  }

 #gas .btn{
    box-shadow: none;
    width: 60px;
    height: 40px;
    background-color: #C04C43;
    color: #fff;
    border-radius: 15px;
    float: left;
 }
 #smoke{
    width: 660px; 
    height: 380px;
    padding: 20px 30px 55px 30px;
    background-color:  #F1EDEE;
    border-radius: 13px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    float:left;
    position: absolute; 
    top:12px; 
    left: 787px; 
    z-index: 2;
    
 }
 #smoke .btn{
    box-shadow: none;
    width: 60px;
    height: 40px;
    background-color: #454545;
    color: #fff;
    border-radius: 15px;
    float: left;
 }
 #water{
    width: 1050px; 
    height: 380px;
    padding: 20px 30px 55px 30px;
    background-color:  #F1EDEE;
    border-radius: 13px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    float:left;
    position: absolute; 
    top:445px; 
    left: 50px; 
    z-index: 2;
 }

 #water .btn{
    box-shadow: none;
    width: 60px;
    height: 40px;
    background-color: #12355B;
    color: #fff;
    border-radius: 15px;
    float: left;

 }
 

 #waterChart {
  width: 1000px;
  height: 390px;
}

 .modal-bg-opacity {
    background-color: rgba(60, 60, 60, 0.2); 
    border: 1px solid #ccc; 
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); 

.btn-read-more{
  margin-right:910px; 
  margin-bottom:60px;
}

#learn-more{
  margin-right:910px; 
  margin-bottom:60px;
  width:165px;

}

.carousel-caption h1,
.carousel-caption h2 {
  float: left;
}

.carousel-caption h2 {
  clear: left; 
  margin-top: 20px;
  line-height: 1;
}
.chartDiv {

  height: 1500px; 
  width: 1500px; 
  position: relative; 
  z-index: 1; 
  overflow: hidden; 
  top: 0; 
  padding: 25px;

}

.chart-bg-gas {
  height: 380px; 
  width: 710px; 
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
              0 6px 20px 0 rgba(0, 0, 0, 0.19);
  position: relative; 
  z-index: 1;  
  margin: 15px 0; 
  border-radius: 13px; 
  overflow: hidden; 
  top: 0; 
  background: #C04C43;
}

.chart-bg-smoke {
  height: 380px; 
  width: 710px; 
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
              0 6px 20px 0 rgba(0, 0, 0, 0.19); 
  position: relative; 
  z-index: 1;  
  margin: 15px 0; 
  border-radius: 13px; 
  overflow: hidden; 
  top: 0; 
  background: #454545;
}

.chart-bg-water {
  height: 380px; 
  width: 1100px; 
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
              0 6px 20px 0 rgba(0, 0, 0, 0.19); 
  position: relative; 
  z-index: 1;  
  margin: 15px 0; 
  border-radius: 13px; 
  overflow: hidden; 
  top: 0; 
  background: #12355B;
}

  #threshold {
    width: 275px; 
    height: 380px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    position: absolute; 
  z-index: 2;  
  border-radius: 13px; 
  top: 445px; 
  left: 1175px;  
  background: #F1EDEE;
  float:left;
}

.container-bg-threshold {
  height: 380px; 
  width: 325px; 
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
              0 6px 20px 0 rgba(0, 0, 0, 0.19); 
  position: relative; 
  z-index: 1;  
  margin: 15px 0; 
  border-radius: 13px; 
  overflow: hidden; 
  top: 0; 
 
  background: #676767;
  float: right;
}

#threshold .btn {
  width: calc(100% - 25%); 
  color: white; 
 
  background: #676767;
  height: 40px;
}

.precautionary-box {
  height: 500px; 
  width: 1420px; 
  padding: 40px; 
  box-shadow: 0 0 30px rgba(31, 45, 61, 0.125); 
  margin: 15px 0; 
  position: relative; 
  z-index: 1; 
  border-radius: 10px; 
  overflow: hidden; 
  top: 0; 
  background: #e8e3e3; 
  margin-top: -650px;
}

.icon-smoke {
  width: 80px; 
  height: 80px; 
  position: absolute; 
  top: 57px; 
  left: 500px; 
  z-index: 2;
}

.icon-gas {
  width: 80px; 
  height: 80px; 
  position: absolute; 
  top: 57px; 
  left: 840px; 
  z-index: 2;
}

.icon-water {
  width: 80px; 
  height: 80px; 
  position: absolute; 
  top: 57px; 
  left: 1185px; 
  z-index: 2;
}

.precautionary-box-1 {
  position: relative; 
  background: #f3f3f3; 
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
              0 6px 20px 0 rgba(0, 0, 0, 0.19);
}


#carousel-item-active {
  background: url("assets/img/user-sf-bg.png");
}

.custom-indicators li {
  width: 20px; 
  height: 20px; 
  margin: 0 5px; 
  background-color: #999; 
  border: 1px solid #fff;
  border-radius: 50%; 
  cursor: pointer;
}

.custom-indicators .active {
  background-color: #fff;
}

.precautionary-box-1 p, h5{
    color: #545353;
}

.modal-header h1{
    color: #545353;
}

.section-title h2,
.box-title h2 {
  color: #545353;
}

#threshold h3 {
  font: 'Poppins, sans-serif';
  font-size: 23px;
  font-weight: bold;
  color: #666666;

}

#threshold h4 {
  font: 'Poppins, sans-serif';
  font-size: 15px;
  font-weight: bold;
  color: #666666;
  margin-top: -8px;

}

.rad-label {
  display: flex;
  position: relative;
  align-items: center;

  border-radius: 100px;
  padding: 14px 16px;
  margin: 10px 0;
  cursor: pointer;
  transition: .3s;
  padding-left: 50px;
}

.rad-label:hover, 
.rad-label:focus-within {
  background: hsla(0, 0%, 80%, .14);
}

.rad-input {
  position: absolute;
  left: 0;
  top: 0;
  width: 1px;
  height: 1px;
  opacity: 0;
  z-index: -1;
}

.rad-design {
  width: 24px;
  height: 20px;
  border-radius: 100px;

  
  background: linear-gradient(to right bottom, rgba(192, 76, 67, 0.9), rgba(192, 76, 67, 0.9));
  position: relative;
}

.rad-design::before {
  content: '';

  display: inline-block;
  width: inherit;
  height: inherit;
  border-radius: inherit;

  background: hsl(0, 0%, 90%);
  transform: scale(1.1);
  transition: .3s;
}

.rad-input:checked+.rad-design::before {
  transform: scale(0);
}

.rad-text {
  color: hsl(0, 0%, 60%);
  margin-left: 14px;
  font-size: 15px;
  font-weight: 600;

  transition: .3s;
}

.rad-input:checked~.rad-text {
  color: #666666;
}

.threshold_levels{ 
margin-top: -115px;
}

  @media screen and (max-width: 576px) {

#hero {
  margin-top: 10px;
  width: 100%;
  background-size: cover; 
  position: relative;
  }

.btn-read-more {
  width: calc(100% - 70%);
  height:auto;
  margin-right:910px; 
  margin-bottom:-50px;
  padding: 5px 12px;
  
}

#learn-more {
  width: calc(100% - 68%);
  height:auto;
  margin-right:910px; 
  margin-bottom:-50px;
  padding: 5px 12px;
  
}

.carousel-caption h1,
  .carousel-caption h2 {
    float: none;
    text-align: left;
    margin-top: 20px;
    line-height: 1; 
  }

  .carousel-caption h1 {
    font-size: 20px;
  }

  .carousel-caption h2 {
    font-size: 10px;
   
  }
.custom-indicators li {
  
    width: 1px;
    margin-bottom: -15px;
  }

#carousel-item-active {
  background: url("assets/img/user-sf-bg-mob.png");
}

.section-title h2{
  font-size: 28px;
  color: #545353;
}

.chartDiv {
  height: 1000px; 
  width: 410px; 
  padding: 7px; 
  width: 100%; 
}

.chart-bg-gas {
  height: 220px; 
  width: 100%; 
  overflow: hidden;
  padding: 20px;
  margin-top: 35px;
}

.chart-bg-smoke {
  height: 220px; 
  width: 100%; 
  overflow: hidden;
  padding: 20px;
  margin-top: 35px;
}

.chart-bg-water {
  height: 220px; 
  width: 100%; 
  overflow: hidden;
  padding: 20px;
  margin-top: 10px;
}


#gas{
    width: calc(100% - 40px);
    height: 230px;  
    position: absolute; 
    left: 20px;
    top:10px; 
  }

#smoke {
    width: calc(100% - 40px);
    height: 230px;  
    position: absolute; 
    left: 20px;
    top:280px; 
}

#water {
    width: calc(100% - 40px);
    height: 230px;  
    position: absolute; 
    left: 20px; 
    top:550px; 
}

#threshold {
    width: calc(100% - 40px);
    height: 150px;  
    position: absolute; 
    left: 20px; 
    top:820px; 
}

#threshold h3 {
  font: 'Poppins, sans-serif';
  font-size: 19px;
}

#threshold h4 {
  font: 'Poppins, sans-serif';
  font-size: 13px;
}

.rad-label {
    display: inline-block;
    margin-right: 10px;
  }

.rad-label 
  .rad-label:last-child {
    margin-right: 0;
  }

  .rad-label, .rad-input {
    margin-top: -15px;
    padding-left: 35px; 
  }

  #threshold .btn {
    width: calc(100% - 85%);
    height: 25px;
    font-size: 13px;
    margin-top: -30px;
    padding: 1px;
  }

  .rad-text {
    font-size:13px;
  }


.container-bg-threshold {
    width: 100%; 
    height: 150px;  
    float: none;
    overflow: hidden;
    padding: 20px;
    margin-top: 20px;
}

#gasChart, 
#smokeChart,
#waterChart { 
  width: 100%; 
  height: auto; 
} 

.threshold_levels{ 
margin-top: -140px;
position: relative;
width: 100%;
    height: auto;
}


#gas .btn, 
#smoke .btn, 
#water .btn {
    width: 50px;
    height: 40px;
}

.precautionary-box {
  margin-top: -10px;
  height: auto; 
  width: 100%; 
  padding: 30px; 
}

.box-title h2 {
  margin-top:-150px;
  color: #545353;
}


  .icon-smoke {
  width: 20%; 
  height: auto; 
  top: 8%;  font-size: ;
  left: 40%;
  position: absolute;  
}

.icon-gas {
  width: 20%; 
  height: auto; 
  top: 38.5%; 
  left: 40%;
  position: absolute;    
}

.icon-water {
  width: 20%; 
  height: auto; 
  top: 69.5%; 
  left: 40%;
  position: absolute;    
}


.precautionary-box-1 {
  margin-top:18px;
  width: 100%;
  
}

.precautionary-box-1 h5 {
  font-size: 23px;
}

.modal-content {
  width:100%;
  height: auto;
  position: absolute;
  top: 50%;
  left: 50%; 
  transform: translate(-50%, -50%);
}

}

    </style>

    </head>


<body>


<?php
  require_once 'header.php';
  ?>
  
<div class="main-header">
  <section id="hero" class="d-flex flex-column justify-content-center align-items-right">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <ol class="carousel-indicators custom-indicators">
        <li data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#heroCarousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#heroCarousel" data-bs-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active" id="carousel-item-active">
          <img src="assets/img/first.png" class="d-block w-100" alt="Image 1" style="opacity:83%;">
          <div class="carousel-caption">
            <h1>Let's be safe together!</h1>
            <h2>SafeHome, your best home buddy.</h2>
            <a href="user-read-more.php" class="btn-read-more scrollto">READ MORE</a>
          </div>
        </div>
        <div class="carousel-item" id="carousel-item3">
          <img src="assets/img/setup.png" class="d-block w-100" alt="Image 5" style="opacity:83%;">
          <div class="carousel-caption">
            <h1 class="device">Device Setup:</h1>
            <h2>Learn how to setup the SafeHome device!</h2>
            <a href="user-device-setup.php" class="btn-read-more scrollto" id="learn-more">LEARN MORE</a>
          </div>
        </div>
        
        <div class="carousel-item" id="carousel-item2">
          <img src="assets/img/device-carousel-2.png" class="d-block w-100" alt="Image 3" style="opacity:83%;">
          <div class="carousel-caption">
          
          </div>
        </div>

      </div>
      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </a>
      <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </a>
    </div>
  </section>
</div>

<div class="main-charts">
<div class="row">
<div class="col-lg-12">
                <div class="section-title">
                    <h2>Dashboard</h2>
                </div>
  <div class="col-lg-12" style="text-align: center;">
      <div class="chartDiv mx-auto">
        <div class="row">

          <div class="col-lg-6 col-md-3">
          <div id="gas">
            <canvas id="gasChart"></canvas>
            <button onclick="fetchDataAndUpdateChart('Gas Sensor')" class="btn">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
              <div class="chart-bg-gas mx-auto">
              </div>
          </div>

          <div class="col-lg-6 col-md-3">
          <div id="smoke">
            <canvas id="smokeChart"></canvas>
            <button onclick="fetchDataAndUpdateChart('Smoke Sensor')" class="btn">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
              <div class="chart-bg-smoke mx-auto">
              </div>
          </div>
</div>

<br>
<div class="row">
          <div class="col-lg-8 col-md-4">
          <div id="water">
            <canvas id="waterChart"></canvas>
            <button onclick="fetchDataAndUpdateChart('Water Sensor')" class="btn" >
                <i class="bi bi-arrow-clockwise"></i>
            </button>
          </div>

              <div class="chart-bg-water mx-auto">
              </div>

          </div>

              <div class="col-lg-4 col-md-2">
                <div id="threshold">
                  <canvas class="thresholdMain"></canvas>

                  <form action="" method="post">
    <div class="threshold_levels">
        <h3>Sensors Sensitivity</h3>
        <?php 
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $selectUser = "SELECT * FROM homeowners where username='$username' and password='$password'";
        $query = mysqli_query($link, $selectUser);

        while($info = mysqli_fetch_assoc($query)){
            $sensorsensitivy = $info['threshold_mode'];
        ?>
        <h4>Current Status: <?php echo ($sensorsensitivy); ?></h4>
        <?php } ?>
        
        <label class="rad-label">
            <input type="radio" class="rad-input" name="threshold_mode" value="Low" <?php echo ($sensorsensitivy === 'Low') ? 'checked' : ''; ?>>
            <div class="rad-design"></div>
            <div class="rad-text">Low</div>
        </label>
        <label class="rad-label">
            <input type="radio" class="rad-input" name="threshold_mode" value="Average" <?php echo ($sensorsensitivy === 'Average') ? 'checked' : ''; ?>>
            <div class="rad-design"></div>
            <div class="rad-text">Average</div>
        </label>
        <label class="rad-label">
            <input type="radio" class="rad-input" name="threshold_mode" value="High" <?php echo ($sensorsensitivy === 'High') ? 'checked' : ''; ?>>
            <div class="rad-design"></div>
            <div class="rad-text">High</div>
        </label><br>
        <input type="hidden" id="sensitivityLevel" value="<?php echo $sensorsensitivy; ?>">
        <button type="submit" class="btn mx-auto rounded" name="update_threshold">Save</button>
    </div>
</form>

                </div>

              <div class="container-bg-threshold mx-auto">
              </div>

              </div>


          </div>
</div>
        </div>
      </div>
    </div>
</div>
</div>

<br><br>
<div class="main-precautions">
<div class="row">
  <div class="col-lg-12" style="text-align: center;">
    <div class="wrapper mx-auto justify-content-center align-items-center">
      <div class="precautionary-box mx-auto">
        <div class="row">
          <div class="box-title col-md-3">
              <br><br><br><br><br><br><br>
              <h2>Quick Start for Precautionary Measures!</h2>
          </div>
          <div class="col-md-3">
            <br><br>
            <div class="icon-smoke">
              <img src="assets/img/fire-icon.png" alt="" class="img-fluid">
            </div>
            <div class="precautionary-box-1">
              <br>
              <h5>Fire Emergency</h5>
              <p>What are the best practices to mitigate the cause of fire emergency situations? What should you consider doing amidst the emergency? What should you do to elevate safetiness inside your household?</p>
              <button type="button" style="float: center;" class="learn-more" data-bs-toggle="modal" data-bs-target="#smokeModal1">Learn More</button>
            </div>
          </div>
          <div class="col-md-3">
              <br><br>
              <div class="icon-gas">
                <img src="assets/img/gas-icon.png" alt="" class="img-fluid">
              </div>
            <div class="precautionary-box-1">
              <br>
              <h5>Gas Leakage Emergency</h5>
              <p>What are the best practices to mitigate the cause of gas leakage emergency situations? What should you consider doing amidst the emergency? What should you do to elevate safetiness inside your household?</p>
              <button type="button" class="learn-more" data-bs-toggle="modal" data-bs-target="#smokeModal2">Learn More</button>
            </div>
          </div>
          <div class="col-md-3">
              <div class="icon-water">
                <img src="assets/img/flood-icon.png" alt="" class="img-fluid">
              </div>
            <br><br>
            <div class="precautionary-box-1">
              <br>
              <h5>Flood Emergency</h5>
              <p>What are the best practices to be prepared when flooding occurs? What should you consider doing amidst the emergency? <br>What should you do to elevate safetiness inside your <br>household?</p>
              <button type="button" class="learn-more" data-bs-toggle="modal" data-bs-target="#smokeModal3">Learn More</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


                      <div class="modal fade modal-bg-opacity" id="smokeModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-4 mods-title" id="exampleModalLabel">Fire Precautions</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="container-main" style="width:100%;">
                                  <div class="container-body">
                                    <form>
                                    <div class="row gx-3 mb-3 div1">
                                    
                                    <div class="col-md-5">
                                      <br><br><br>
                                      <img src="assets/img/before-fire.png" alt=""  class="img-fluid">
                                    </div>
                                    
                                    <div class="col-md-7">
                                     <br><h6 style="text-align:center; color:#EA1135">Before Fire Emergency</h6>
                                     <ul class="before-infos">
                                      <li>Be sure to be prepared and to have a emergency plan ahead. </li>
                                      <li>Know your evacuation routes, transportation and a place to stay outside of the evacuation zone.</li>
                                      <li>If living in a highrise, use stairs instead of elevator and know how to evacuate out of the building.</li>
                                      <li>Store emergency supplies that you can grab quickly. It should contain goods that will be good for atleast three days.</li>
                                      <li>Install your SafeHome device at most prone place/s and always check the status of the house for monitoring.</li>
                                      <li>Store your important documents and any personal important items in a safe and easily accessible place.</li>
                                      <li>Make sure to store flammable or combustible materials away from children and in a safe place.</li>
                                     </ul>
                                    </div>

                                  </div>
                                  <div class="row gx-3 mb-3 div2">
                                    
                                    <div class="col-md-7">
                                    <br><h6 style="text-align:center; color:#F1EDEE">During Fire Emergency</h6>
                                    <ul class="during-infos">
                                      <li>Do not panic. Go out and stay out. Stay alert and aware, follow you escape plan and don't stop.</li>
                                      <li>If closed doors and handles are warm, user an alternative exit.</li>
                                      <li>If you encounter an approaching fire or trapped, make sure to dial 911 and call for help.</li>
                                      <li>To signal for help, look for an open window and wave a brightly colored cloth or use a flashlight.</li>
                                      <li>Crawl under low smoke. When smoke is aggresively disturbing, use wet towel and cover your mouth and nose.</li>
                                      <li>Wear facemasks for double protection from harmful particles.</li>
                                      <li>Perform 'Stop, Drop, and Roll' if ever your cloth get caught by fire and prevent it from escalating further damage.</li>
                                      <li>Always stay with your family and keep your disaster safety kit on hand.</li>
                                      <li>Once you get outside, make sure to remain calm and evacuate. Communicate with your family and check on one another.</li>
                                    </ul>
                                    </div>
                                    
                                    <div class="col-md-5">
                                      <br><br><br><br>
                                      <img src="assets/img/during-fire.png" alt=""  class="img-fluid">
                                    </div>

                                  </div>
                                  <div class="row gx-3 mb-3 div3">
                                    
                                    <div class="col-md-5">
                                      <br><br><br><br>
                                      <img src="assets/img/after-fire.png" alt=""  class="img-fluid">
                                    </div>
                                    
                                    <div class="col-md-7">
                                    <br><h6 style="text-align:center; color:#F1EDEE">After Fire Emergency</h6>
                                    <ul class="after-infos">
                                      <li>Always be informed and check for latest news and updates for information about the fire.</li>
                                      <li>You can enter your house if the autorithies give permission to do so.</li>
                                      <li>Contact your families and friends and let them know about the situation.</li>
                                      <li>With public health guidelines, check on someone who may require special assistance and get them treated by medical professionals.</li>
                                      <li>Get a hold to your local goverment authorities for temporary shelter in case you cannot stay at your house due to the damages.</li>
                                      <li>Be cautious and take precautions when cleaning your property. Wear protective gear like mask, gloves, safety glasses and boots.</li>
                                      <li>Take a photograph and inventory for ruined furnitures, appliances, books, etc. and contact insurance company if necessary.</li>
                                      <li>Ensure that water and food are safe and not contaminated in any possible way.</li>
                                    </ul> 
                                    </div>

                                  </div>
                                    </form>

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      

          
                      <div class="modal fade modal-bg-opacity" id="smokeModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-4 mods-title" id="exampleModalLabel">Gas Leakage Precautions</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <div class="container-main" style="width:100%;">
                                  <div class="container-body">
                                    <form>
                                    <div class="row gx-3 mb-3 div1">
                                    
                                    <div class="col-md-5">
                                      <br><br><br>
                                      <img src="assets/img/before-gas.png" alt=""  class="img-fluid">
                                    </div>
                                    
                                    <div class="col-md-7">
                                     <br><h6 style="text-align:center; color:#EA1135">Before Gas Leakage Emergency</h6>
                                     <ul class="before-infos">
                                      <li>Make sure how to turn off the valve of the LPG and locate it in a proper area.</li>
                                      <li>Store all the cylinders may it be full or empty in an upright position in extremely ventillated area.</li>
                                      <li>Do not smoke near the LPG and keep all combustible materials or flammable items away from it.</li>
                                      <li>Keep children away from the gas stove and gas connection.</li>
                                      <li>If possible, provide a fire fighting equipment like extinguishers and dry powder. Make it easilly accessible.</li>
                                      <li>Always close your gas tap before you go to bed at night.</li>
                                      <li>Always close the main control valve if you are not going to be home for a day or more.</li>
                                      <li>Always ensure the rubber tube fits correctly and regularly check it for any cuts/damages.</li> 
                                     </ul>
                                    </div>

                                  </div>
                                  <div class="row gx-3 mb-3 div2">
                                    
                                    <div class="col-md-7">
                                    <br><h6 style="text-align:center; color:#F1EDEE">During Gas Leakage Emergency</h6>
                                    <ul class="during-infos">
                                      <li>Do not panic. For a suspected leakage, do not switch on any lights, open all doors, windows, and close any open flames.</li>
                                      <li>Do not allow any naked flame, spark or smoking near the leakage point.</li>
                                      <li>Stop cooking immediately if you smell a gas leakage.</li>
                                      <li>Do not tamper with any component of the gas connection.</li>
                                      <li>Isolate the electrical supply from the outside source.</li>
                                      <li>Try to isolate the cylinder to an open space and cover it with a wet cloth.</li>
                                      <li>Contact the local gas company to rectify the leak.</li>
                                      <li>If the smell of gas continues, evacuate the building and call the police.</li>
                                      <li>If the leak cannot be stopped or a significant leak has occurred, evacuate the premises.</li> 
                                    </ul>
                                    </div>
                                    
                                    <div class="col-md-5">
                                      <br><br>
                                      <img src="assets/img/during-gas.png" alt=""  class="img-fluid">
                                    </div>

                                  </div>
                                  <div class="row gx-3 mb-3 div3">
                                    
                                    <div class="col-md-5">
                                      <img src="assets/img/after-gas.png" alt=""  class="img-fluid">
                                    </div>
                                    
                                    <div class="col-md-7">
                                    <br><h6 style="text-align:center; color:#F1EDEE">After Gas Leakage Emergency</h6>
                                    <ul class="after-infos">
                                      <li>If someone inhaled the gas and have difficulty breathing, immediately take them outside for fresh air.</li>
                                      <li>For any skin contact and burns, cover them with a clean cloth and rinse with cold water.</li>
                                      <li>In case someone needed a special assistance, take them to a medical professional to be treated accordingly.</li>
                                      <li>Do not disposed the cylinder just yet, return it to your local dealer and let them handle it.</li>
                                    </ul>
                                    </div>

                                  </div>
                                    </form>

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      
            
                      <div class="modal fade modal-bg-opacity" id="smokeModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-4 mods-title" id="exampleModalLabel">Flood Precautions</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <div class="container-main" style="width:100%; height: auto;">
                                  <div class="container-body">
                                    <form>
                                    <div class="row gx-3 mb-3 div1">
                                    
                                    <div class="col-md-5">
                                      <br><br><br><br>
                                      <img src="assets/img/before-flood.png" alt=""  class="img-fluid">
                                    </div>
                                    
                                    <div class="col-md-7">
                                     <br><h6 style="text-align:center; color:#EA1135">Before Flood Emergency</h6>
                                     <ul class="before-infos">
                                      <li>Always be prepare and make sure to have an emergency plan.</li>
                                      <li>Practice drills and talk with your family about what should you do during a flood.</li>
                                      <li>Know and practice the best route for evacuation areas.</li>
                                      <li>Monitor local weather alert and follow instructions if told to evacuate.</li>
                                      <li>Have a handy emergency kit that will be good for atleast three days.</li>
                                      <li>Make sure to obtain home insurance that covers flood emergency.</li>
                                      <li>Protect your valuables in a container. For large appliances, raise them and place above potential water level.</li>
                                      <li>Put sealant on areas where water can possibly leak and enter the house.</li>
                                      <li>Check all the water drainage and ensure that nothing is covering it.</li>
                                     </ul>
                                    </div>

                                  </div>
                                  <div class="row gx-3 mb-3 div2">
                                    
                                    <div class="col-md-7">
                                    <br><h6 style="text-align:center; color:#F1EDEE">During Flood Emergency</h6>
                                    <ul class="during-infos">
                                      <li>Be alert and always make sure that your family are updated about the current situation and possible flood warning reports.</li>
                                      <li>Be prepared to evacuate whenever given a notice by the local authority.</li>
                                      <li>In case that a flood or flash flood is stated within your area, head for a higher place and stay there.</li>
                                      <li>Avoid walking and driving though flooded water, they could be deeper that you think.</li>
                                      <li>Keep children and pets away from flood water.</li>
                                      <li>Be cautious especially at night as it is harder to recognize flood danger.</li>
                                      <li>Be aware or streams, drainage channels, canyons, and other areas known to flood suddenly.</li>
                                      <li>Turn off utilities and main switches or valves. </li>
                                      <li>Disconnect electrical appliances and do not touch any electrical equipment if you are wet or standing in water.</li>
                                    </ul>
                                    </div>
                                    
                                    <div class="col-md-5">
                                      <br><br><br>
                                      <img src="assets/img/during-flood.png" alt=""  class="img-fluid">
                                    </div>

                                  </div>
                                  <div class="row gx-3 mb-3 div3">
                                    
                                    <div class="col-md-5">
                                      <br><br><br>
                                      <img src="assets/img/after-flood.png" alt=""  class="img-fluid">
                                    </div>
                                    
                                    <div class="col-md-7">
                                    <br><h6 style="text-align:center; color:#F1EDEE">After Flood Emergency</h6>
                                    <ul class="after-infos">
                                      <li>Always be informed for further instructions from the community officials. Listen to current emergency reports to your radio and local news channels.</li>
                                      <li>As long as authorities hasn't told you to return home, better stay at the evacuation center as it is safer.</li>
                                      <li>Contact your insurance company and talk about the damages done by the flood.</li>
                                      <li>Be extra cautious when entering buildings, there may be a hidden damage to its foundation.</li>
                                      <li>Maintain good hygiene while doing flood cleanup. Wear protective clothing like rubber boots, safety glasses, hard hat, rubber gloves, and a dust mask.</li>
                                      <li>Make sure to do not use water that could be possibly contaminated.</li>
                                      <li>Discard any food which have been in contact with flood water.</li>
                                      <li>Do not use appliances just yet until all electrical components are dry.</li>
                                      <li>Dispose your damaged items properly.</li> 
                                    </ul>    
                                    </div>

                                  </div>
                                    </form>

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
</div>

<script>
  
let sensitivityLevel = document.getElementById('sensitivityLevel').value;
let gasChartInstance;
let smokeChartInstance;
let waterChartInstance;

let gasDataIndex = 0;
let smokeDataIndex = 0;
let waterDataIndex = 0; 

let sensorData = [];

let gasData = [];
let gasDataFetched = 0;
let smokeDataFetched = 0;
let waterDataFetched = 0;

let userID = <?php echo $id; ?>;

let wValue;

  function fetchData() {
      return fetch('jsonLogs.json')
          .then(response => response.json());

          console.log(response);
  }

function updateChart(sensorLabel, idToDisplay) {
const sensorDataPoints = sensorData.filter(entry => entry.label === sensorLabel && entry.id == userID);

const latestData = sensorDataPoints.slice(-5);

const labels = latestData.map(entry => formatTimestamp(entry.timestamp)); 
const values = latestData.map(entry => entry.value);
const thresholdValueGas = getThresholdValue(sensitivityLevel, 'Gas Sensor');
const thresholdValueSmoke = getThresholdValue(sensitivityLevel, 'Smoke Sensor');

const wValues = latestData.map(entry => getValueFromLabel(entry.value));

let chartInstance;
if (sensorLabel === 'Gas Sensor') {
  chartInstance = gasChartInstance;
} else if (sensorLabel === 'Smoke Sensor') {
  chartInstance = smokeChartInstance;
} else if (sensorLabel === 'Water Sensor') {
  chartInstance = waterChartInstance;
}

if (chartInstance) {
  chartInstance.destroy();
}

if(sensorLabel == 'Gas Sensor') { 
  gasChartInstance = new Chart(document.getElementById('gasChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Gas Sensor (ppm)',
            data: values,
            borderColor: 'rgba(192, 76, 67, 1)', 
            backgroundColor: 'rgba(192, 76, 67, 0.4)', 
            borderWidth: 2, 
            fill: true, 
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins, sans-serif',
                        size: 17, 
                        weight: 'bold',
                    }
                }
            },
            title: {
                font: {
                    family: 'League Spartan, sans-serif',
                    size: 18, 
                }
            }
        },
        scales: {
            y: {
                suggestedMin: 0,
                suggestedMax: 500, 
                ticks: {
                    stepSize: 50,
                    font: {
                        family: 'Poppins, sans-serif',
                        size: 14, 
                    }
                }
            }
        }
    },
    plugins: [{
        afterDraw: (chart) => {
            const ctx = chart.ctx;
            const yAxis = chart.scales.y;
            const yValue = thresholdValueGas; 
            const yPixel = yAxis.getPixelForValue(yValue);

            ctx.save();
            ctx.strokeStyle = 'red';
            ctx.lineWidth = 2;
            ctx.beginPath();
              ctx.moveTo(yAxis.right, yPixel); 
            ctx.lineTo(chart.width, yPixel);
            ctx.stroke();
            ctx.restore();
          }
    }]
});

const isMobile = window.matchMedia("(max-width: 767px)").matches;

if (isMobile) {
    gasChartInstance.options.plugins.legend.labels.font.size = 15;
    gasChartInstance.options.plugins.title.font.size = 7;
    gasChartInstance.options.scales.y.ticks.font.size = 10;

    gasChartInstance.update(); 



    } else if(sensorLabel == 'Smoke Sensor') { 
    smokeChartInstance = new Chart(document.getElementById('smokeChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: labels, 
            datasets: [{
                label: 'Smoke Sensor (ppm)',
                data: values,
                borderColor: 'rgba(69, 69, 69, 1)',
            backgroundColor: 'rgba(69, 69, 69, 0.4)', 
            borderWidth: 2, 
            fill: true, 
            }]
        },
        options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins, sans-serif',
                        size: 17, 
                        weight: 'bold',
                    }
                }
            },
            title: {
                font: {
                    family: 'League Spartan, sans-serif',
                    size: 18,
                }
            }
        },
        scales: {
            y: {
                suggestedMin: 0,
                suggestedMax: 500, 
                ticks: {
                    stepSize: 50,
                    font: {
                        family: 'Poppins, sans-serif',
                        size: 14, 
                    }
                }
            }
        }
    },
    plugins: [{
        afterDraw: (chart) => {
            const ctx = chart.ctx;
            const yAxis = chart.scales.y;
            const yValue = thresholdValueSmoke; 
            const yPixel = yAxis.getPixelForValue(yValue);

           
            ctx.save();
            ctx.strokeStyle = 'black'; 
           
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(yAxis.right, yPixel); 
            ctx.lineTo(chart.width, yPixel);
            ctx.stroke();
            ctx.restore();
        }
    }]
});

const isMobile = window.matchMedia("(max-width: 767px)").matches;

if (isMobile) {
    smokeChartInstance.options.plugins.legend.labels.font.size = 15;
    smokeChartInstance.options.plugins.title.font.size = 7;
    smokeChartInstance.options.scales.y.ticks.font.size = 10;

    smokeChartInstance.update();
}
    } else if(sensorLabel == 'Water Sensor') { 
      waterChartInstance = new Chart(document.getElementById('waterChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: labels, 
        datasets: [{
            label: 'Water Sensor (mS/m)',
            data: wValues, 
            borderColor: 'rgba(18, 53, 91, 1)',
            backgroundColor: 'rgba(18, 53, 91, 0.4)',
            borderWidth: 2,
            fill: true,
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    font: {
                        family: 'Poppins, sans-serif',
                        size: 17, 
                        weight: 'bold',
                    }
                }
            },
            title: {
                font: {
                    family: 'League Spartan, sans-serif',
                    size: 18, 
                }
            }
        },
        scales: {
            y: {
                suggestedMin: 100,
                suggestedMax: 300, 
                ticks: {
                    stepSize: 100, 
                    font: {
                        family: 'Poppins, sans-serif',
                        size: 14.5, 
                        weight: 'bold',
                    },
                    callback: function (value, index, values) {
                        if (value === 100) return 'Low';
                        if (value === 200) return 'Medium';
                        if (value === 300) return 'High';
                        return value;
                    }
                }
            }
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem) {
                    return tooltipItem.yLabel.toFixed(10); 
                }
            }
        },
         responsive: true,
            maintainAspectRatio: false, 
          
            width: 1750,
            height: 390,
    },
    
        
    
});

const isMobile = window.matchMedia("(max-width: 767px)").matches;

if (isMobile) {
    waterChartInstance.options.plugins.legend.labels.font.size = 15;
    waterChartInstance.options.plugins.title.font.size = 7;
    waterChartInstance.options.scales.y.ticks.font.size = 10;

    waterChartInstance.update();
}

  }
}

function fetchDataAndUpdateChart(sensorLabel, idToDisplay) {
  const sensorDataPoints = sensorData.filter(entry => entry.label === sensorLabel && entry.id == idToDisplay);

fetchData().then(data => {
  sensorData = data;


  if (getSensorDataIndex(sensorLabel) > sensorDataPoints?.length) {
      console.log(`There are less than 5 available data points for the ${sensorLabel}.`);
  } else {
     
      updateChart(sensorLabel);
  }
});
}


function getThresholdValue(sensitivityLevel, sensorType) {
    switch (sensorType) {
        case 'Gas Sensor':
            switch (sensitivityLevel) {
                case 'Low':
                    return 300;
                case 'Average':
                    return 150;
                case 'High':
                    return 100;
                default:
                    return 150;
            }
        case 'Smoke Sensor':
            switch (sensitivityLevel) {
                case 'Low':
                    return 450;
                case 'Average':
                    return 300;
                case 'High':
                    return 250;
                default:
                    return 300;
            }
    }
}
 function fetchAndAutoUpdate(sensorLabel, idToDisplay) { 
    const sensorDataPoints = sensorData.filter(entry => entry.label === sensorLabel && entry.id == idToDisplay);

    fetchData().then(data => {
        sensorData = data;

        if (getSensorDataIndex(sensorLabel) > sensorDataPoints?.length) {
            console.log(`There are less than 5 available data points for the ${sensorLabel}.`);
        } else {
            updateChart(sensorLabel);
        }
    });
}
        
function startAutoUpdate(sensorLabel, idToDisplay) {  
    setInterval(() => {
        fetchAndAutoUpdate(sensorLabel, idToDisplay);
    }, 60000); 
}

function formatTimestamp(timestamp) {
const date = new Date(timestamp);
return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

function getValueFromLabel(label) {
if (label === 'Low') {
  return 100;
} else if (label === 'Medium') {
  return 200;
} else if (label === 'High') {
  return 300;
}
}

function getSensorDataIndex(sensorLabel) {
if (sensorLabel === 'Gas Sensor') {
  return gasDataIndex;
} else if (sensorLabel === 'Smoke Sensor') {
  return smokeDataIndex;
} else if (sensorLabel === 'Water Sensor') {
  return waterDataIndex;
}
}
updateChart('Gas Sensor', userID);
updateChart('Smoke Sensor', userID);
updateChart('Water Sensor', userID);

startAutoUpdate('Gas Sensor', userID);
startAutoUpdate('Smoke Sensor', userID);
startAutoUpdate('Water Sensor', userID);

fetchAndAutoUpdate('Gas Sensor', userID);
fetchAndAutoUpdate('Smoke Sensor', userID);
fetchAndAutoUpdate('Water Sensor', userID);

</script>

<script>
    // Initialize the carousel when the page loads
    document.addEventListener("DOMContentLoaded", function () {
      var carousel = new bootstrap.Carousel(document.getElementById("heroCarousel"), {
        interval: 5000, 
        pause: "hover",
      });
    });
</script>



</body>
</html>
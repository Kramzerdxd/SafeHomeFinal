<?php 
require "config.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];

?>

<!doctype html>
<html lang="en">
<head>

    
    <title>Profile</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/user-view-profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin=""/>
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" crossorigin=""></script>

<style>
        #map{
    width: 100%;
    height: 33.5vh;
    overflow: hidden;
    }

    img {
  max-width: 300px;
  height: auto;
  margin-top: 10px;
}

  .address-cell {
    overflow-x: auto;   
    max-width: 600px; 
  }

    .change-password-button {
        align-self: flex-end;
        margin-top: 330px; 
        font-size: 0.8rem;
        color:#039BE5;
        position: relative;
    }

    .content {
  border-right: 1px solid rgba(33, 40, 50, 0.125);
}

#geoDiv{
  padding: 0px 10px 10px 50px;
}

.wrapper {
    padding: 30px 10px 10px 5px;
    width: 100%;
    max-width: 1100px;
    height: auto;
    position: relative;
}


@media(max-width: 576px) {

  .btn.btn-save i {
    display: none;
  }

  .btn.btn-save{
    float: left;
    margin-left: 25px;
    width: 100%;
    height: 60px;
    position: absolute;
  }

  .change-password-button {
    background-color: #039BE5;
    color: white; 
    width: 130px;
    height: auto;
    padding: 10px;
    text-align: center;
    border-radius: 50px;
    cursor: pointer;
    margin-top: -5px;
    font-size: 0.8rem;
    float: right;
    margin-right: 25px;
    border-radius: 25px;
    box-shadow: 3px 3px 3px #b1b1b1,
            -3px -3px 3px #fff;
        letter-spacing: 0.5px;
  }

  #geoDiv{
  padding: 0px 0px 0px 25px;
  
  }

  .content {
    border-right: none; 
  }

  .wrapper {
    margin-top: -385px;
    height: auto; 
    padding: 15px;
    
  }

  #hero {
    padding: 25px;
  }

  .table {
    margin-top: 30px;
  }

  .table td{
    width: 1px;
  }

  #map{
    width: 100%;
    height: 34vh;
    }



}



    </style>
    </head>

<body>
  <link href="assets/img/favicon.ico" rel="favicon">
  <link href="assets/img/favicon.ico" rel="favicon">

  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<?php
  require_once 'header.php';
?> 
</body>

    
<body>
<section id="hero" class="d-flex justify-content-center align-items-center mx-auto">
    <div class="wrapper text-center text-xl-center">
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

  <main class="container mb-5">
      <div class="row">
  <div class="col-lg-3 content mx-auto">
    <?php 
    if ($picture == '') {
        echo '<img class="img-account-profile" style="border-radius:50%; width: 120px; height: 120px; object-fit: cover; object-position: 25% 25%;" src="assets/img/default-avatar.png">';
    } else {
        echo '<img class="img-account-profile" style="border-radius:50%; width: 120px; height: 120px; object-fit: cover; object-position: 25% 25%;" src="assets/uploaded-img/' . $picture . '">';
    }
    ?>
    <p style="margin-top:5px; font-size:18px;">
      <?php echo ucwords($firstname) . ' ' . ucwords($lastname); ?>
    </p>
    <a href="user-edit-profile.php"><button class="btn btn-save" style="background-color: #039BE5; width: 120px; color: white; margin-top:-5px; position: relative;">
       <i class="bi bi-pencil-square"></i><a href="user-edit-profile.php"> Edit Profile</a></button></a>
       <div class="change-password-button" onclick="location.href='modal-page.php'">
  Change Password
</div>

  </div>
  <div class="col-lg-9">
    <div class="row">
      <div class="col-lg-12">
        <table class="table" style="text-align:left; color:#4c4d4f;">
            <th colspan="2">Account Information</th>
          <tbody>
          <tr>
              <td>User ID</td>
              <td class="field-cell"><?php echo ucwords($id) ; ?></td>
            </tr>
            <tr>
              <td>Full Name</td>
              <td class="field-cell"><?php echo ucwords($firstname) . ' ' . ucwords($lastname); ?></td>
            </tr>
            <tr>
              <td>Username</td>
              <td><?php echo ($username); ?></td>
            </tr>
            <tr>
              <td>Email Address</td>
              <td class="field-cell"><?php echo ($email); ?></td>
            </tr>
            <tr>
              <td>Phone Number</td>
              <td><?php echo ($contact); ?></td>
            </tr>
            <tr>
              <td>Full Address</td>
              <td class="address-cell"><?php echo ucwords($address); ?></td>
            </tr>
          </tbody>
        </table>
  </div>
        <div class="row" >
      <div class="col-lg-12" id="geoDiv" style="margin:auto; width: 100%; height: 30vh; position: relative;">
        <div id="map"></div> 
  </div>
     </div>
    </div>
  </div>
</div>
        <?php
        }
        ?>
        </main>
    </div>
</section>

<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'safehome');
 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$myId = $id;

$sql1 = "SELECT longitude FROM homeowners WHERE id='" . $myId . "'";
$sql2 = "SELECT latitude FROM homeowners WHERE id='" . $myId . "'";

$result1 = mysqli_query($conn, $sql1);
$result2 = mysqli_query($conn, $sql2);

$data1 = mysqli_fetch_assoc($result1);
$data2 = mysqli_fetch_assoc($result2);


mysqli_close($conn);

$longi = $data1['longitude'];
$lati = $data2['latitude'];
?>
  

<script> 

var jsonData1 = <?php echo $longi; ?>;
var jsonData2 = <?php echo $lati; ?>;

var popup = L.popup();
let map = new L.map('map');
map.setView([jsonData2, jsonData1], 17);

let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);

var marker = L.marker([jsonData2, jsonData1]).addTo(map);

</script>
  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>



</body>
</html>
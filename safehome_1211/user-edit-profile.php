<?php 
include "config.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];


if (isset($_POST['remove'])) {
    $updateQuery = "UPDATE homeowners SET picture='' WHERE username='$username' AND password='$password'";
    mysqli_query($link, $updateQuery);

    function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("activity_history.log", $format, FILE_APPEND);
     }
          
     $log_details = "Removed Profile Picture";
     logs($link, $username, $log_details);
    
    header("Location: user-edit-profile.php");
    exit();
}

$firstname = $lastname = $contact = $address = $picture = $oldPass = $newPass = $confirmPass = $inputNewPass = $latitude = $longitude = $geo_url = $hidden_lat = $hidden_long = $hidden_url = '';

$firstname_err = $lastname_err =  $contact_err = $address_err = $picture_err = $lat_err = $long_err = '';

if (isset($_POST['update_account_details'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
  

     $input_firstname = trim($_POST["firstname"]);
     if(empty($input_firstname)){
         $firstname_err = "Please enter a firstname.";
     } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
         $firstname_err = "Please enter a valid firstname.";
     } else{
         $firstname = ucwords($input_firstname);
     }

    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a lastname.";
    } elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastname_err = "Please enter a valid lastname.";
    } else{
        $lastname = ucwords($input_lastname);
    }

    $input_contact = trim($_POST["contact"]);
    if(empty($input_contact)){
        $contact_err = "Please enter the contact amount.";     
    } elseif(!filter_var($input_contact, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0][9][0-9]{9}$/")))){
        $contact_err = "Please enter valid Contact no. (09XXXXXXXXX)";
    } else{
        $contact = $input_contact;
    } 
      
     if(empty($firstname_err) && empty($lastname_err)  
      && empty($contact_err)){
     $updateQuery = "UPDATE homeowners SET firstname='$firstname', lastname='$lastname', contact='$contact' WHERE username='$username' AND password='$password'";
     
     function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("activity_history.log", $format, FILE_APPEND);
     }
     if($statement = mysqli_query ($link, $updateQuery)){
          
       $log_details = "Edited Account Details";
       logs($link, $username, $log_details);
         header("Location: user-edit-profile.php");
         exit();
      } else{
       echo "Oops! Something went wrong. Please try again later.";
      }
     mysqli_stmt_close($stmt);
    
 }
 mysqli_close($link);
 }

 if (isset($_POST['update_address'])) {
  $address = $_POST['address'];
  $latitude = $_POST['hidden_lat'];
  $longitude = $_POST['hidden_long'];
  $geo_url =$_POST['hidden_url'];

   $input_address = trim($_POST["address"]);
   if(empty($input_address)){
       $address_err = "Please enter an address.";     
   } elseif(!filter_var($input_address, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s\,\.\#]+$/")))){
       $address_err = "Re-enter address";
   }else{
       $address = $input_address;
   } 

    $input_latitude = trim($_POST["hidden_lat"]);
    if(empty($input_latitude)){
        $lat_err = "Please pin your location";     
    }else{
        $latitude = $input_latitude;
    }

    $input_longitude = trim($_POST["hidden_long"]);
    if(empty($input_longitude)){
        ?> 
        <script>
            alert("Please pin your location");
        </script>
        <?php
        $long_err = "Please pin your location";     
    } else{
        $longitude = $input_longitude;
    }

     $input_geourl= trim($_POST["hidden_url"]);
     if(empty($input_geourl)){
         $lat_err = "Please pin your location";     
     }else{
         $geo_url = $input_geourl;
     }
   
    
   if(empty($address_err) && empty($lat_err) && empty($long_err)){
   $updateQuery = "UPDATE homeowners SET address='$address', longitude = '$longitude', latitude ='$latitude', geo_url='$geo_url' WHERE username='$username' AND password='$password'";
   
   if($statement = mysqli_query ($link, $updateQuery)){
    function logs($link, $username, $details)
   {
     date_default_timezone_set('Asia/Manila');
     $date = date('F d, Y h:i:sA');
   
       $format = $date . "\t" . $username . "\t" . $details . "\n";
   file_put_contents("activity_history.log", $format, FILE_APPEND);
   }
        
     $log_details = "Updated Address";
     logs($link, $username, $log_details);
       header("Location: user-edit-profile.php");
       exit();
    } else{
     echo "Oops! Something went wrong. Please try again later.";
    }
   mysqli_stmt_close($stmt);
  
}
mysqli_close($link);
}

if (isset($_POST['submit'])) {
 if (isset($_FILES["picture"]["name"])) {
    $id = $_POST["id"];

    $picture = $_FILES['picture']['name'];
    $picture_size = $_FILES['picture']['size'];
    $picture_tmp_name = $_FILES['picture']['tmp_name'];
    $picture_folder = 'assets/uploaded-img/';

    $image_file_type = strtolower(pathinfo($picture, PATHINFO_EXTENSION));
    $allowed_extensions = array('jpg', 'jpeg', 'png');

    if (!in_array($image_file_type, $allowed_extensions)) {
        $picture_err = "Invalid image format. Only JPG, JPEG, and PNG files are allowed.";

    } elseif ($picture_size > 2000000) { 
        $picture_err = "Image size is too large. Maximum file size allowed is 2MB.";
      
    } else {
      
        $unique_filename = uniqid() . '.' . $image_file_type;
        $picture_folder = 'assets/uploaded-img/' . $unique_filename;

        function logs($link, $username, $details)
     {
       date_default_timezone_set('Asia/Manila');
       $date = date('F d, Y h:i:sA');
     
         $format = $date . "\t" . $username . "\t" . $details . "\n";
     file_put_contents("activity_history.log", $format, FILE_APPEND);
     }
       
        if (move_uploaded_file($picture_tmp_name, $picture_folder)) {
          
            $picture = $unique_filename; 
            $updateQuery = "UPDATE homeowners SET picture='$picture' WHERE username='$username' AND password='$password'";

            $log_details = "Uploaded A Profile Picture";
            logs($link, $username, $log_details);
            mysqli_query($link, $updateQuery);

        } else {
            $picture_err =  "Failed to upload the picture.";
        }
    }
}
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">  

  <link href="assets/css/user-edit-profile.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <script src="https://code.jquery.com/jquery-3.6.4.min.js" 
     integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" 
     crossorigin="anonymous"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.4/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha3/js/bootstrap.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/5.5.3/css/ionicons.min.css">
 
     <style>
        #map{
    width: 100%;
    height: 40vh;
    
    }

    img {
  max-width: 300px;
  height: auto;
  margin-top: 10px;
}

#geoDiv{
    margin:auto; 
    width: 100%; 
    height: 50vh; 
    padding: 5px 10px 10px 10px;
}

.upload{
      width: 120px;
      position: relative;
      margin: auto;
      text-align: center;
    }
    .upload img{
      border-radius: 50%;
      border: 3px solid gray;
      height: 120px;
      width:120px;
    }
    .upload .rightRound{
      position: absolute;
      bottom: 0;
      right: 0;
      background: #0294DB;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .leftRound{
      position: absolute;
      bottom: 0;
      left: 0;
      background: #dc3545;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .fa{
      color: white;
    }
    .upload input{
      position: absolute;
      transform: scale(2);
      opacity: 0;
    }
    .upload input::-webkit-file-upload-button, .upload input[type=submit]{
      cursor: pointer;
      }

      #is-invalid {
        border: 2px solid red;
    }

    #fieldDivs-picture {
        margin-top: -5px;
        position: absolute;
        padding-left: 16%;
    }
   
    #fieldDivs {
        margin-top: -3px;
        position: absolute;
    }

    #fieldErrs {
        color: red;
        font-size: 12px;
    }

    .modal-content {
     background: #f2f6fc;
    }

    .wrapper {
      width: calc(100% - 40%); 
      height: auto; 
      margin:auto; 
      margin-top:100px;
    }

@media screen and (max-width: 576px) {


      .wrapper {
      width: 100%;
      height: auto; 
  }

      #fieldDivs-picture{
      padding-left: 10%;
  }

      .account-details{
        padding: 1px;
  }

    }

    </style>  
</head>

<?php
  require_once 'header.php';
?> 
    
<body>

<section id="hero" class="d-flex justify-content-center align-items-center mx-auto">

    <div class="wrapper text-center text-md-left">
        <?php 
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $selectUser = "SELECT * FROM homeowners where username='$username' and password='$password'";
        $query = mysqli_query($link, $selectUser);
        while($info = mysqli_fetch_assoc($query)){
            $id = $info['id'];
            $firstname = $info['firstname'];
            $lastname = $info['lastname'];
            $address = $info['address'];
            $contact = $info['contact'];
            $picture = $info['picture'];
            $latitude = isset($_POST['hidden_lat']) ? $_POST['hidden_lat'] : null;
            $longitude = isset($_POST['hidden_long']) ? $_POST['hidden_long'] : null;
            $geo_url = isset($_POST['hidden_url']) ? $_POST['hidden_url'] : null;
            
            
        ?>
      <div class="row-12">
      <div class="col-1">
      <a href="user-view-profile.php"><i class="bi bi-arrow-left" style="color:#69707a; font-size:30px;"></i></a>
      </div>
    <form class="form" id = "form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="post" style="margin-top:100px;">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
      <div class="upload" style="margin:auto; margin-top:-130px; background-color: transparent;">
      <?php 
        if ($picture == '') {
            echo '<img id="image" class="img-account-profile" style= "object-fit: cover; object-position: 25% 25%;" src="assets/img/default-avatar.png">';
        } else {
            echo '<img id="image" class="img-account-profile" style="object-fit: cover; object-position: 25% 25%;" src="assets/uploaded-img/' . $picture . '">';
        }
        ?>

        <div class="rightRound" id = "upload">
          <input type="file" name="picture" id = "picture">
          <i class = "fa fa-pen"></i>
        </div>

        <div class="leftRound" id = "cancel" style = "display: none;">
          <i class = "fa fa-times"></i>
        </div>
        <div class="rightRound" id = "confirm" style = "display: none;">
          <input type="submit" name="submit">
          <i class = "fa fa-check"></i>
        </div>
      </div>
        <div id="fieldDivs-picture"><span id="fieldErrs"><?php echo $picture_err;?></span></div>
      <button type="submit" class="remove" name="remove" value="Remove" style="margin-top:20px; font-size:13.5px;">Remove Profile</button>
    </form>
    </div>
    <div class="row account-details">
    <div class="col-9" style="margin:auto; text-align:left;"><br>
        <div class="card-header" style="  font-weight: 450; text-align: left;
        color:#69707a; border-bottom: 1px solid rgba(33, 40, 50, 0.125);">Account Details</div><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                     
                        <div class="row gx-3 mb-3">
                       
                            <div class="col-md-6">
                              <div>
                                <label class="small mb-1" style="color:#69707a;">First name</label> 
                                <input class="form-control" id="<?php echo (!empty($firstname_err)) ? 'is-invalid' : 'fName'; ?>" type="text" name="firstname" value="<?php echo ucwords($firstname); ?>" placeholder="Enter your first name" required>
                                </div>
                                <div id="fieldDivs"><span id="fieldErrs"><?php echo $firstname_err;?></span></div>
                            </div>

                            <div class="col-md-6">
                              <div>
                                <label class="small mb-1" style="color:#69707a;">Last name</label>
                                <input class="form-control" id="<?php echo (!empty($lastname_err)) ? 'is-invalid' : 'lastname'; ?>" type="text" name="lastname"  value="<?php echo ucwords($lastname); ?>" placeholder="Enter your last name" required>
                            </div>
                            <div id="fieldDivs"><span id="fieldErrs"><?php echo $lastname_err;?></span></div>
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                                <label class="small mb-1" style="color:#69707a;">Username</label>
                                <input class="form-control" type="text" name="username" value="<?php echo ($username); ?>" placeholder="Enter your username" disabled>
                            </div>
                        
                             <div class="col-md-6">
                              <div>
                                <label class="small mb-1" style="color:#69707a;">Contact Number</label>
                                <input class="form-control" id="<?php 
                          
                                ?>" type="number" name="contact" value="<?php echo ($contact); ?>" placeholder="Enter your phone number">
                            </div>
                           <?php echo $contact_err;?>
                            </div>
                        </div>

                      <div class="mb-3">
                          <div>
                                <label class="small mb-1" style="color:#69707a;">Email Address</label>
                                <input class="form-control" type="email" name="email" value="<?php echo ($email); ?>" placeholder="Enter your email address" disabled>
                          </div>
                        </div>
                        <div class="row gx-3 mb-3">

                        <button class="btn btn-save mx-auto" id="updateDetails" style = "background-color: #0294DB; width: 200px;
                                float: right; margin-top: 5px; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#saveModal1">Save Changes</button>
                  
                                <div class="modal fade" id="saveModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                          <div class="modal-body" style=" text-align:center;">
                                          <i class="bi bi-question-circle fa-4x animated rotateIn mb-4" style="color: #C04C43;"></i>
                                          <p class="text-center p">Do you want to save changes?</p>
                                          </div>
                                          <div class="modal-footer mx-auto">
                                            <div class="row">
                                              <div class="col-6">
                                              <button type="button" class="btn btn-danger waves-effect mx-auto rounded " style="width: 100px; color: white; height: 40px;" data-bs-dismiss="modal">Cancel</button>
                                              </div>
                                              <div class="col-6">
                                              <button type="submit" class="btn btn-danger mx-auto rounded" name="update_account_details" style="width: 100px; color: white; height: 40px;">Save</button>
                                            </div>
                                            </div>
                                            </div>
                                      </div>
                                  </div>
                        


                        </div> 
                            </form>
    </div>
    </div>


    <div class="row">
    <div class="col-9" style="margin:auto; text-align:left;"><br>
        <div class="card-header" style="  font-weight: 450; text-align: left;
        color:#69707a; border-bottom: 1px solid rgba(33, 40, 50, 0.125);">Pin Address</div><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                      <div class="mb-3">
                        <div>
                            <label class="small mb-1" style="color:#69707a;">Full Address</label>
                            <input class="form-control" id="fullAdd<?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value ="<?php echo $address; ?>" type="text" name="address" value="<?php echo ucwords($address); ?>" placeholder="Enter your location">

                            <input type="hidden" id="hidden_lat" name="hidden_lat" class="form-control" value="<?php echo ($hidden_lat); ?>">
                            <input type="hidden" id="hidden_long" name="hidden_long" class="form-control"> 
                            <input type="hidden" id="hidden_url" name="hidden_url" class="form-control">
                          </div>
                        <div id="fieldDivs"><span id="fieldErrs"><?php echo $address_err;?></span></div>
                      </div>
<div class="col-lg-12" id="geoDiv">
        <div id="map"></div> 
    </div>
                        <div class="row gx-3 mb-3">
                        <button class="btn btn-save mx-auto" id="updateAddress" style = "background-color: #0294DB; width: 200px;
                                float: right; margin-top: -45px; color: white;" type="button" data-bs-toggle="modal" data-bs-target="#saveModal2">Update Address</button>
                                <div class="modal fade" id="saveModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                          <div class="modal-body" style=" text-align:center;">
                                          <i class="bi bi-question-circle fa-4x animated rotateIn mb-4" style="color: #C04C43;"></i>
                                          <p class="text-center p">Do you want to save changes?</p>
                                          </div>
                                          <div class="modal-footer mx-auto">
                                            <div class="row">
                                              <div class="col-6">
                                              <button type="button" class="btn btn-danger waves-effect mx-auto rounded " style="width: 100px; color: white; height: 40px;" data-bs-dismiss="modal">Cancel</button>
                                              </div>
                                              <div class="col-6">
                                              <button type="submit" class="btn btn-danger mx-auto rounded" name="update_address" style="width: 100px; color: white; height: 40px;">Save</button>
                                            </div>
                                            </div>
                                            </div>
                                      </div>
                                  </div>
                              </div>
                        </div> 
                            </form>
    </div>
    </div>
        <?php
        }
        ?>
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


<script type="text/javascript">
      document.getElementById("picture").onchange = function(){
        document.getElementById("image").src = URL.createObjectURL(picture.files[0]); 

        document.getElementById("cancel").style.display = "block";
        document.getElementById("confirm").style.display = "block";

        document.getElementById("upload").style.display = "none";
      }

      var userImage = document.getElementById('image').src;
      document.getElementById("cancel").onclick = function(){
        document.getElementById("image").src = userImage; 

        document.getElementById("cancel").style.display = "none";
        document.getElementById("confirm").style.display = "none";

        document.getElementById("upload").style.display = "block";
      }
    </script>
<script> 

var jsonData1 = <?php echo $longi; ?>;
var jsonData2 = <?php echo $lati; ?>;

var popup = L.popup();
let map = new L.map('map');
map.setView([jsonData2, jsonData1], 17);

let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
map.addLayer(layer);

let iconOpt = {
    draggable:true
}

function onMapClick(e) {
       popup
        .setLatLng(e.latlng)
        .setContent(e.latlng.toString())
        .openOn(map);
    lat = e.latlng.lat;
    long = e.latlng.lng;
  
    $('#hidden_long').val(long);
    $('#hidden_lat').val(lat);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'getdata.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('latlong=' + encodeURIComponent(latlong));

    console.log(lat + ", " + long);
}


function addrClick(e) {
  var latlng = e.latlng;

  var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latlng.lat + '&lon=' + latlng.lng;

  fetch(url)
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {
      var address = data.display_name;

      var geo_url = 'http://maps.google.com/maps?f=q&q=' + latlng.lat + ',%20' + latlng.lng + ''; 
      $('#hidden_url').val(geo_url);

      document.getElementById('fullAdd').value = address;
    })
    .catch(function(error) {
      console.log(error);
    });
}


map.on('click', function(e) {

addrClick(e); 
onMapClick(e);

});

</script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
</html>
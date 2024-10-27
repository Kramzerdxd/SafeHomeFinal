<?php 
    include "config.php";
    session_start();

   
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header('Location: modal-page.php');
        exit();
    }

    if (!isset($_SESSION['code_verified']) || $_SESSION['code_verified'] !== true) {
        $_SESSION['info'] = "Please verify your code first.";
        header('Location: modal-page-code.php');
        exit();
    }

    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    $oldPass = $newPass = $confirmPass = $inputNewPass = '';
    $oldPass_err = $newPass_err = $confirmPass_err = '';

    if (isset($_POST['update_password'])) {
    
    $newPass = trim($_POST["newPass"]);
    if (empty($newPass)) {
        $newPass_err = "Please enter valid password.";
    } elseif (strlen($newPass) < 8) {
        $newPass_err = "Password must have a minimum of 8 characters";
    } elseif (!preg_match("/(?=.*\d)/", $newPass)) {
        $newPass_err = "Password must contain at least one digit";
    } elseif (!filter_var($newPass, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*\d)[a-zA-Z0-9\S]+$/")))) {
        $newPass_err = "Please enter valid password.";
    }

   
    $confirmPass = trim($_POST["confirmPass"]);
    if (empty($confirmPass)) {
        $confirmPass_err = "Please confirm password.";
    } elseif ($confirmPass !== $newPass) {
        $confirmPass_err = "Passwords do not match!";
    }

    $oldPass = trim($_POST["oldPass"]);
    $salt = "b2a6ec273b45b1d0ab06130cb4e2e62d"; 
    $hashedOldPass = md5($salt . $oldPass);
    if (empty($oldPass)) {
        $oldPass_err = "Please enter current password.";
    } elseif ($hashedOldPass !== $password) {
        $oldPass_err = "Passwords do not match!";
    }

    if (empty($oldPass_err) && empty($newPass_err) && empty($confirmPass_err)) {
      
        $salt = "b2a6ec273b45b1d0ab06130cb4e2e62d"; 
        $code = 0;
        $email = $_SESSION['email']; 
       
        $hashedOldPass = md5($salt . $oldPass);
        $result = mysqli_query($link, "SELECT password FROM homeowners WHERE username='$username' and password ='$hashedOldPass'");

        function logs($link, $username, $user_details)
        {
            date_default_timezone_set('Asia/Manila');
            $date = date('F d, Y h:i:sA');
        
            $format = $date . "\t" . $username . "\t" . $user_details . "\n";
        file_put_contents("activity_history.log", $format, FILE_APPEND);
        }

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $storedPassword = $row['password'];
            if ($hashedOldPass == $storedPassword) {
                $hashedNewPass = md5($salt . $newPass);
?>
                <script>
             alert("\nPassword Changed Successfully! \nYour account will automatically logout, you will be able to login now with your new password.");
             session_start();
                localStorage.removeItem('username');
                localStorage.removeItem('password');
                localStorage.removeItem('email');
                localStorage.removeItem('code_verified');
                localStorage.removeItem('email_confirmed');
             </script>
             <?php

            $updatePass = "UPDATE homeowners SET code = $code, password='$hashedNewPass' WHERE username='$username'";
            mysqli_query($link, $updatePass);

            $log_details = "Changed Password";
            logs($link, $username, $log_details);

            ?>
            <script>
                window.location.href = 'login.php';
                </script>
            <?php

            }
        } else {
            header("Location: user-edit-profile.php"); 
        } 
    } 
    mysqli_close($link);
    }

    ?>

    <!doctype html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
        <link href="assets/css/user-home-change-pass.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
        crossorigin=""/>

    <link href="https://fonts.googleapis.com/css?family=League+Spartan:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">  
    <link href="assets/css/user-home-change-pass.css" rel="stylesheet">

        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
        crossorigin=""></script>
        

        <style>

            #is-invalid {
            border: 2px solid red;
        }

        #fieldDivs {
            margin-top: -3px;
            position: absolute;
        }

        #fieldErrs {
            color: red;
            font-size: 12px;
        }

        #passwordConditions{
            position: absolute;
            margin-top: -3px;
            text-align: left; 
        }

        .valid-condition {
            color: green;
            font-size: 12px;
        }
        .invalid-condition {
            color: gray;
            font-size: 12px;
        }

        .modal-content {
        background: #f2f6fc;
        }

        .wrapper {
      width: 450px; 
    }

        #hero {
            margin-top:35px;
        }

        @media screen and (max-width: 576px) {


        .wrapper {
            width: 800px;
            }

        .password-container input{
            font-size: 0.7rem;
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
                $email = $info['email'];
                $address = $info['address'];
                $contact = $info['contact'];
                $picture = $info['picture'];
            ?>
            
        <div class="row">
        <div class="col-9" style="margin:auto; text-align:left;"><br>
            <div class="card-header" style="font-weight: 500; text-align: left; margin-top:-20px; color:#69707a; border-bottom: 1px solid rgba(33, 40, 50, 0.125);">Change Password</div><br>
            <?php 
                        if(isset($_SESSION['info'])){
                            ?>
                            <div class="alert alert-success text-center">
                                <?php echo $_SESSION['info']; ?>
                            </div>
                            <?php
                        }
                        ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                <div>
                    <label class="small mb-1" style="color:#69707a;" for="oldPass">Current Password</label>
                    <div class="password-container" style="position: relative;">
                        <input class="form-control" id="oldPass" type="password" name="oldPass" placeholder="Enter your current password">
                        <i class="fas fa-eye" id="toggleCurrentPassword" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                        <div id="fieldDivs"><span id="fieldErrs"><?php echo $oldPass_err;?></span></div>
                </div>
                </div>
                <div class="mb-3">
                <div>
                    <label class="small mb-1" style="color:#69707a;" for="newPass">New Password</label>
                    <div class="password-container" style="position: relative;">
                        <input class="form-control" id="newPass" type="password" name="newPass" placeholder="Enter your new password">
                        <i class="fas fa-eye" id="toggleNewPassword" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                </div>
                </div>
                <div class="mb-3">
                <div>
                    <label class="small mb-1" style="color:#69707a;" for="confirmPass">Confirm Password</label>
                    <div class="password-container" style="position: relative;">
                        <input class="form-control" id="confirmPass" type="password" name="confirmPass" placeholder="Confirm your new password">
                        <i class="fas fa-eye" id="toggleConNewPassword" style="cursor: pointer; color:#69707a; position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                </div>
                <div id="passwordConditions"> <span id="fieldErrs"><?php echo $confirmPass_err;?></span> </div>
                <br>
                <div id="passwordConditions"><span id="fieldErrs"><?php echo $newPass_err;?></span></div>
                <br>
                </div>
                <div class="row gx-3 mb-3">
                    <button class="btn btn-save mx-auto" style="background-color: #0294DB; width: 200px; float: right; margin-top: 5px; color: white;" type="submit"  name="update_password" data-bs-toggle="modal" data-bs-target="#passModal1">Change Password</button>
                  
                </div>
            </form>
        
        </div>
            </div>
            <?php
            }
            ?>
            </div>
    </section>

    <script>
    const toggleCurrentPassword = document.querySelector('#toggleCurrentPassword');
    const current_password = document.querySelector('#oldPass');

    toggleCurrentPassword.addEventListener('click', function (e) {
        const type = current_password.getAttribute('type') === 'password' ? 'text' : 'password';
        current_password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
    </script>

    <script>
    const toggleNewPassword = document.querySelector('#toggleNewPassword');
    const new_password = document.querySelector('#newPass');

    toggleNewPassword.addEventListener('click', function (e) {
        const type = new_password.getAttribute('type') === 'password' ? 'text' : 'password';
        new_password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
    </script>

    <script>
    const toggleConNewPassword = document.querySelector('#toggleConNewPassword');
    const con_new_password = document.querySelector('#confirmPass');

    toggleConNewPassword.addEventListener('click', function (e) {
        const type = con_new_password.getAttribute('type') === 'password' ? 'text' : 'password';
        con_new_password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
    </script>


    <script>
    var passwordInput = document.getElementById("newPass");
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>

            </body>
    </html>

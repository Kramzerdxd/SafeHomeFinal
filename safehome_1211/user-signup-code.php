<?php
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');


session_start();


$code_err = '';

$verification_error = "";

if (isset($_POST['check'])) {
    $_SESSION['success-info'] = "";
    $otp_code = mysqli_real_escape_string($link, $_POST['otp']);
    $check_code = "SELECT * FROM homeowners WHERE code = $otp_code";
    $code_res = mysqli_query($link, $check_code);
    
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $username = $fetch_data['username']; 
        $id = $fetch_data['id']; 
        $code = 0;
        $verification_status = 'verified';
        
        
        $update_otp = "UPDATE homeowners SET code = $code, verification_status = '$verification_status' WHERE code = $fetch_code";
        $update_res = mysqli_query($link, $update_otp);
        
        if ($update_res) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

        
            $emailMessage = "Hello " . $username . ",<br><br>";
            $emailMessage .= "Your account has been successfully signed up and verified.<br><br>";
            $emailMessage .= "Your user ID: " . $id . "<br><br>";
            $emailMessage .= "Thank you for choosing SafeHome.<br><br>";
            
            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'safehome42023@gmail.com';
            $mail->Password = 'tlitclmjwfqvvgpd';
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587;

            $mail->SetFrom('safehome42023@gmail.com', 'SafeHome');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Account Confirmation';
            $mail->Body = $emailMessage;

            if ($mail->send()) {
                $_SESSION['success-info'] = "Your account has been successfully verified. Check your email for more details.";
            } else {
                $verification_error = "Failed to send confirmation email. Please try again later.";
            }

            header('location: login.php');
            exit();
        } else {
            $code_err = "Failed while updating code!";
        }
    } else {
        $code_err = "You've entered an incorrect code!";
    }
}




?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/user-signup-otp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Email Confirmation</title>
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
           Email Confirmation
            </div>
            <form method="POST" autocomplete="off">
          
                        
                        <div class="alert alert-success text-center">
                            
                        </div>
                       
              
                    <div class="form-group">
                        <div>
                        <input class="form-control" type="number" name="otp" placeholder="Enter verification code" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $code_err;?></span></div>
                    </div>
                    <div class="form-group">
                        <input class="btn mt-3" type="submit" name="check" value="Submit">
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
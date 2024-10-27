<?php
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();
$username = $email = $email_err = "";
$salt = "b2a6ec273b45b1d0ab06130cb4e2e62d";
  
function hideEmailAddress($email)
{
    $em   = explode("@",$email);
    $name = implode(array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);
    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
}


if(isset($_POST['check-email'])){
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $check_email = "SELECT * FROM homeowners WHERE email='$email'";
    $run_sql = mysqli_query($link, $check_email);
    if(mysqli_num_rows($run_sql) > 0){
        $code = rand(999999, 111111);
        $insert_code = "UPDATE homeowners SET code = $code WHERE email = '$email'";
        $run_query =  mysqli_query($link, $insert_code);
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->isHTML();
        $mail->Username = 'safehome42023@gmail.com';
        $mail->Password = 'tlitclmjwfqvvgpd';
        $mail->SetFrom('safehome42023@gmail.com', 'SafeHome');
        $mail->Subject = "Password Reset Code";
        $mail->Body = '<p>Your password reset code is: <b style="font-size:30px;">' . $code . '</b></p>';
        $mail->addAddress($email);
        $mail->Send($run_query);

            if($run_query){
                $info = "We've sent a password reset otp to your email - " .hideEmailAddress($email);
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: user-reset-pass.php');
                exit();
            }else{
                $email_err = "Failed while sending code!";
            }
    }else{
        $email_err = "This email address does not exist!";
    }
} 

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/user-forgot-pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>

    .form-control {
        padding-left: 10px; 
        margin-bottom: 20px; 
        border-radius: 10px; 
        width: 100%; 
        padding: 10px 10px 10px 10px; 
        border-color: #3d343468; 
    }

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


  @media screen and (max-width: 576px) {
        .wrapper form {
            width: 100%;
            height: auto;
        }

        .form-control {
        width:100%;
        padding: 10px 10px 10px 10px; 
    }
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
            Forgot Password
            </div>

                <form class="p-3 mt-3 form-group" method="post" autocomplete="">
                    <div>
                    <div class="d-flex align-items-center">
                        <input class="form-control" type="email" name="email" placeholder="Enter your email" required value="<?php echo $email ?>">
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $email_err;?></span></div>
                    </div>
                        <div class="col-sm-12">
                        <input class="btn mt-3" type="submit" name="check-email" value="Continue">
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

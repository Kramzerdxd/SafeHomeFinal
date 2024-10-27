<?php
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];

$email = $email_err = "";
$show_modal = true;

function hideEmailAddress($email)
{
    $em   = explode("@", $email);
    $name = implode(array_slice($em, 0, count($em) - 1));
    $len  = floor(strlen($name) / 2);
    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

if (isset($_POST['check-email'])) {
    $submitted_email = mysqli_real_escape_string($link, $_POST['email']);
    $username = $_SESSION['username'];

    $check_email = "SELECT email FROM homeowners WHERE username ='$username'";
    $run_sql = mysqli_query($link, $check_email);

    if ($run_sql && mysqli_num_rows($run_sql) > 0) {
        $row = mysqli_fetch_assoc($run_sql);
        $email = $row['email'];

        if ($submitted_email == $email) {

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
            $mail->Subject = "Change Password Code";
            $mail->Body = '<p>Your change password code is: <b style="font-size:30px;">' . $code . '</b></p>';
            $mail->addAddress($email);

            if ($mail->Send()) {
                $_SESSION['email_confirmed'] = true;
                $info = "We've sent a change password otp to your email - " . hideEmailAddress($email);
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: modal-page-code.php');
                exit();
            } else {
                $email_err = "Failed while sending code!";
            }
        } else {
            $email_err = "Email does not match!";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email</title>
    <link rel="stylesheet" href="assets/css/user-forgot-pass.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
          <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <style>

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    max-width: 25%;
    max-height: 80%;
    overflow: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.modal-header h2 {
    margin: 0;
}

.modal-body {
    padding-top: 10px;
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

        
    .modal-content {
    max-width: 70%;
    }
    }
        </style>
</head>

<body>

    <div class="modal-overlay" id="modalOverlay" style="<?php echo ($show_modal ? 'display: block;' : 'display: none;'); ?>">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Change Password</h4>
            <button id="closeModalButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?php if ($show_modal) { ?>
                <form method="post" autocomplete="">
                    <div>
                        <br>
                        <div class="d-flex align-items-left">
                            <label style="color:#4c4d4f;">Please verify your email first!</label>
                        </div>
                        <div>
                            <input class="form-control" type="email" name="email" placeholder="Enter your email" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                        </div>
                        <div id="fieldDivs"><span id="fieldErrs"><?php echo $email_err; ?></span></div>
                    </div>
                    <input class="btn mt-3" type="submit" name="check-email" value="Continue">
                </form>
                <div id="externalContent"></div>
            <?php } else { ?>
                <script> closeModal(); </script>
            <?php } ?>
        </div>
    </div>
</div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const modalOverlay = document.getElementById("modalOverlay");
    const closeModalButton = document.getElementById("closeModalButton");
    const openModalButton = document.getElementById("openModalButton");
    const externalContentContainer = document.getElementById("externalContent");

    function openModal() {
        modalOverlay.style.display = "block";

        fetch("user-view-profile.php")
            .then((response) => response.text())
            .then((data) => {
                externalContentContainer.innerHTML = data;
            })
            .catch((error) => {
                console.error("Error fetching external content:", error);
                externalContentContainer.innerHTML;
            });
    }

    function closeModal() {
        modalOverlay.style.display = "none";
        externalContentContainer.innerHTML = ""; 
        window.location.href = 'user-view-profile.php';
    }

    closeModalButton.addEventListener("click", closeModal);
    openModalButton.addEventListener("click", openModal);
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

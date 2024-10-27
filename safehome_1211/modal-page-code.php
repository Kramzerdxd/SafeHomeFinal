<?php
require_once "config.php";
require_once('PHPMailer/PHPMailerAutoload.php');

session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

if (!isset($_SESSION['email_confirmed']) || $_SESSION['email_confirmed'] !== true) {
    $_SESSION['info'] = "Please confirm your email first!.";
    header('Location: modal-page.php');
    exit();
}


$username = $_SESSION['username'];
$password = $_SESSION['password'];


$email = $_SESSION['email'];
$username = $email = $otp_err = "";
$salt = "b2a6ec273b45b1d0ab06130cb4e2e62d";
$show_modal = true; 

if(isset($_POST['check-reset-otp'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($link, $_POST['otp']);
    $check_code = "SELECT * FROM homeowners WHERE code = $otp_code";
    $code_res = mysqli_query($link, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $_SESSION['code_verified'] = true;
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        header('location: user-home-change-pass.php');
        exit();
    }else{
        $otp_err = "You've entered incorrect code!";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Code</title>
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
                <form method="POST" autocomplete="off">
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <div>
                        <input class="form-control" type="number" name="otp" placeholder="Enter code" required style="padding-left: 10px; margin-bottom: 20px; border-radius: 10px; width: 100%; padding: 10px 10px 10px 10px; border-color: #3d343468; ">
                    </div>
                    <div id="fieldDivs"><span id="fieldErrs"><?php echo $otp_err;?></span></div>
                    </div>
                    <div class="form-group">
                        <input class="btn mt-3" type="submit" name="check-reset-otp" value="Submit">
                    </div>
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

        fetch("https://example.com")
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
        window.location.href = 'modal-page.php';
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

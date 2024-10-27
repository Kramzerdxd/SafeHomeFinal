<?php 
session_start();

date_default_timezone_set('Asia/Manila');
$date = date('F d, Y h:i:sA');

function logs($date, $username, $details)
{
    $format = $date . "\t" . $username . "\t" . $details . "\n";
    file_put_contents("activity_history.log", $format, FILE_APPEND);
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $details = "Logout";
    logs($date, $username, $details);

    if (isset($_POST['button'])) {
        function logs($date, $username, $details)
        {
            $format = $date . "\t" . $username . "\t\t" . $details . "\n";
            file_put_contents("activity_history.log", $format, FILE_APPEND);
        }    }
}

unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['email']);

header("location: login.php");
?>


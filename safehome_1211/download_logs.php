<?php

require_once "config.php";
session_start();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    header("Location: login.php");
    exit;
}

$sql = "SELECT gas_sensor, smoke_sensor, water_sensor, timestamp FROM sensor_logs WHERE user_id = '$id' ORDER BY timestamp DESC";

$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $filename = "sensor_logs.txt";

   
    header('Content-Type: text/plain'); 
    header('Content-Disposition: attachment; filename="' . $filename . '"');


    $output = fopen('php://output', 'w');

 
    while ($row = mysqli_fetch_array($result)) {

        $data = array(
            'Gas: ' . $row['gas_sensor'],
            'Smoke: ' . $row['smoke_sensor'],
            'Water: ' . $row['water_sensor'],
            'Timestamp: ' . $row['timestamp']
        );


        fwrite($output, implode("\n", $data) . "\n\n");
    }


    fclose($output);
} else {
    echo "No records to download.";
}


mysqli_close($link);

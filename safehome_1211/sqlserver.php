<?php
include "config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $jsonData = json_decode($data, true);

    if ($jsonData !== null) {

        $gasSensorValue = $jsonData['Gas Sensor'];
        $smokeSensorValue = $jsonData['Smoke Sensor'];
        $waterSensorValue = $jsonData['Water Level'];
        $id = $jsonData['Id'];


        $servername = "localhost"; 
        $username = "root";
        $password = "";
        $database = "safehome"; 

        $conn = new mysqli($servername, $username, $password, $database);

       
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        
        $sql = "INSERT INTO sensor_logs (user_id, gas_sensor, smoke_sensor, water_sensor, timestamp)
                VALUES ('$id', '$gasSensorValue', '$smokeSensorValue', '$waterSensorValue', NOW())";

        if ($conn->query($sql) === TRUE) {
            echo "Data inserted into MySQL database successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo 'Invalid JSON data.';
    }
} else {
    echo 'Invalid request method.';
}
?>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $jsonData = json_decode($data, true);

    if ($jsonData !== null) {

        echo "Received JSON: ";
        print_r($jsonData);
        
$timezone = new DateTimeZone('Asia/Manila'); 


$date = new DateTime('now', $timezone);


$timestamp = $date->format("Y-m-d H:i:s");

       
        $jsonData['Timestamp'] = $timestamp;
        
       
        $existingData = file_exists('jsonLogs.json') ? file_get_contents('jsonLogs.json') : '[]';

        
        $dataArray = json_decode($existingData, true);

   
        $dataArray[] = array(
            'label' => 'Gas Sensor',
            'value' => $jsonData['Gas Sensor'],
            'id' => $jsonData['Id'],
            'timestamp' => $jsonData['Timestamp']
        );

        $dataArray[] = array(
            'label' => 'Smoke Sensor',
            'value' => $jsonData['Smoke Sensor'],
            'id' => $jsonData['Id'],
            'timestamp' => $jsonData['Timestamp']
        );

        $dataArray[] = array(
            'label' => 'Water Sensor',
            'value' => $jsonData['Water Level'],
            'id' => $jsonData['Id'],
            'timestamp' => $jsonData['Timestamp']
        );

      
        $jsonString = json_encode($dataArray, JSON_PRETTY_PRINT);

 
        file_put_contents('jsonLogs.json', $jsonString);

     
        echo PHP_EOL;

        echo 'Data received and saved to data.json on the server.';
    } else {
        echo 'Invalid JSON data.';
    }
} else {
    echo 'Invalid request method.';
}


function getFormattedTimestamp() {
    date_default_timezone_set('UTC');
    return date('Y-m-d H:i:s', time());
}
?>

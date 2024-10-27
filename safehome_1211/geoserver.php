<?php
$id = ''; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
   
    echo "No 'id' provided in the URL.";
    exit;
}

$host = 'localhost'; 
$dbname = 'safehome';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_GET['action']) && $_GET['action'] === 'fetchData') {
    $sql = "SELECT geo_url, address, contact, id FROM homeowners WHERE id = $id";

    $result = $conn->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo "Invalid request.";
}

$conn->close();

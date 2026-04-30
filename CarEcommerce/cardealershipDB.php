<?php
$host = "localhost";     // usually localhost
$user = "root";          // default in XAMPP/WAMP
$password = "";          // default is empty
$database = "car_dealership";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "*"; //confirm connection
?>
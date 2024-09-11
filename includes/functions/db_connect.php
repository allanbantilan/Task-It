<?php

$serverName = 'localhost';
$userName = 'root';
$password = '';
$dbName = 'task';

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    echo "Error connecting :" . $conn->error;
} else {
    // echo "connected";
}
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// $servername = "cosc360.ok.ubc.ca";
// $username = "11888757";
// $password = "11888757";
// $dbname = "db_11888757";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
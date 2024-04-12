<?php

// Set timezone
date_default_timezone_set('America/Vancouver');

$currentTime = time() - 7;  // Get the current time as a Unix timestamp 7s ago

// Get user ID
$uid = $_GET['uid'];

// database connection logic 
include 'db_config.php';

// Query to get product names
$sql = "SELECT pid FROM watchList 
        WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

// Store product names in an array
$productIDs = array();
while ($row = $result->fetch_assoc()) {
    $productIDs[] = $row['pid'];
    // echo $row['pid'];
}
$stmt->close();

// Loop through each pid
foreach ($productIDs as $productID) {
    // echo $productID . "\n";
    // echo "Curr Time: ". $currentTime . "\n";
    // Query for individual product review
    $sql = "SELECT MAX(date) as last_updated FROM review WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    // echo "last updated: " . $row["last_updated"] . "\n";

    // convert timestamp string to unix time
    $last_updated = strtotime($row["last_updated"]); 

    // echo "in unix: " . $last_updated . "\n";
    if ($last_updated > $currentTime) {
        echo "Product with ID " . $productID . " has a new review!\n";
    } 
    $stmt->close();
}
$conn->close();

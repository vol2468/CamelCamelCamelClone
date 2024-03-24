<?php
// database connection logic 
include 'db_config.php';

// Query for current last_updated
$sql = "SELECT MAX(last_updated) FROM category"; 
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$lastUpdateTime = strtotime($row['last_updated']);

session_start();

// Simulate storing when the last check happened
if (!isset($_SESSION['last_check'])) {
    $_SESSION['last_check'] = time(); // First time
}

$needsUpdate = ($lastUpdateTime > $_SESSION['last_check']);
$_SESSION['last_check'] = time();  // Update last check time

echo $needsUpdate ? 'true' : 'false'; 


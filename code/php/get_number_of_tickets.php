<?php
// Database connection setup
include_once ("db_config.php");

// Prepare and execute SQL query
$sql = "SELECT COUNT(*) as total_rows FROM inquiry";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Handle potential errors
if (!$result) {
    http_response_code(500);
    echo "Failed to fetch number of users.";
    exit;
}

// Output the number
echo $row['total_rows'];

// Close resources
$conn->close();


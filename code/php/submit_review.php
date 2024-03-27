<?php
// Database connection 
include_once("db_config.php");

// Access variables
$uid = $_POST['uid'];
$pid = $_POST['pid'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

// Prepare SQL statement (using a prepared statement for security)
$sql = "INSERT INTO review (uid, pid, rate, comment) 
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind parameters 
$stmt->bind_param("iiss", $uid, $pid, $rating, $comment);

// Execute the statement
if ($stmt->execute()) {
    echo "Review submitted successfully!"; 
} else {
    echo "Error submitting review: " . $stmt->error; 
}

// Close resources
$stmt->close();
$conn->close();


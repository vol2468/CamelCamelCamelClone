<?php
// Database connection 
include_once("db_config.php");

// Access variables
$d_uid = $_POST['d_uid'];
$pid = $_POST['pid'];

// Prepare SQL statement (using a prepared statement for security)
$sql1 = "DELETE FROM review
        WHERE review.uid = ? AND review.pid = ?";
$stmt1 = $conn->prepare($sql1);

// Bind parameters 

$stmt1->bind_param("ii", $d_uid, $pid);

// Execute the statement
if (!$stmt1->execute()) {
    echo "Error deleting review: " . $stmt1->error; 
} 

$stmt1->close();

// Query to update review count
$sql2 = "SELECT COUNT(*) FROM review WHERE pid = ?";
$stmt2 = $conn->prepare($sql2);

// Bind parameters 
$stmt2->bind_param("i", $pid);

// Execute the statement
$stmt2->execute();

// Get the result 
$result = $stmt2->get_result();
$count = mysqli_fetch_assoc($result)['COUNT(*)'];

echo $count;

// Send as JSON response
// header('Content-Type: application/json');
// echo json_encode(array('rowCount' => $rowCount)); 

$stmt2->close();

// Close connection
$conn->close();


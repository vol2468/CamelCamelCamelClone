<?php
// Get user ID
$uid = $_GET['uid'];

// database connection logic 
include 'db_config.php';

// Query to get row count
$sql = "SELECT COUNT(*) as rowCount FROM review
        WHERE review.uid != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo json_encode($row); // Output row count as JSON
} else {
  echo "0 results";
}

$stmt->close();
$conn->close();
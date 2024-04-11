<?php
/// database connection logic 
include 'db_config.php';

// Get uid & pid from POST request
$uid = $_POST['uid'];
$pid = $_POST['pid'];

// Check if item already exists
$sql1 = "SELECT COUNT(*) as total_rows FROM watchList WHERE uid = ? AND pid = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ii", $uid, $pid);
$stmt1->execute();
$stmt1->bind_result($totalRows);
$stmt1->fetch();
if ($totalRows > 0) {
    echo 'This item is already in your price watch!'; 
    echo $result->num_rows; // Value exists
    exit;
} else {
    // Add item to price watch
    $stmt1->close();
    $sql2 = "INSERT INTO watchList (uid, pid, threshold) 
        VALUES (?, ?, 18.00)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ii", $uid, $pid);
    if ($stmt2->execute()) {
        echo "Price watch added successfully!";
    } else {
        echo "Error adding price watch: " . $stmt2->error;
    }
}
// Close resources
$stmt2->close();
$conn->close();

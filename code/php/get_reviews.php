<?php
// Database connection setup
include_once ("db_config.php");

// Get product & user ID
$pid = $_GET['pid'];
$uid = $_GET['uid'];
$usertype = $_GET['usertype'];

// Prepare and execute SQL query 1
$sql = "SELECT * FROM review 
        JOIN user ON review.uid = user.uid 
        WHERE review.pid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pid);
$stmt->execute();
$result = $stmt->get_result();

// Handle potential errors
if (!$result) {
    http_response_code(500);
    echo "Failed to fetch reviews";
    exit;
}

// Format reviews as HTML 
$reviewsHtml = "";
while ($row = $result->fetch_assoc()) {
    $reviewsHtml .= "<div class='review'>";
    $reviewsHtml .= "<div class='star-btn-container'>";
    if ($row['uid'] == $uid || $usertype == 1) {
       
        $reviewsHtml .= "<button class='placeholder-btn'></button>";

    }
    $reviewsHtml .= "<div class='star'>";
    for ($i = 0; $i < $row['rate']; $i++) {
        $reviewsHtml .= "★";  // Filled star
    }
    for ($i = 0; $i < (5 - $row['rate']); $i++) {
        $reviewsHtml .= "☆";  // Empty star 
    }
    $reviewsHtml .= "</div>";
    if ($row['uid'] == $uid || $usertype == 1) {
        $reviewsHtml .= "<form class='delete-btn-form' method='POST'>";
        $reviewsHtml .= "<input type='hidden' name='delete-uid' value='" . $row['uid'] . "'>";
        $reviewsHtml .= "<button class='delete-review-btn' type='submit'><i class='fa-regular fa-trash-can'></i></button>";
        $reviewsHtml .= "</form>";
    }
    $reviewsHtml .= "</div>";
    $reviewsHtml .= "<p><strong>" . $row['uname'] . "</strong></p>";
    $reviewsHtml .= "<p>" . $row['comment'] . "</p>";
    $reviewsHtml .= "<p><small>Posted on " . date("Y-m-d", strtotime($row['date'])) . "</small></p>";
    $reviewsHtml .= "</div>";
}

// Output the HTML
echo $reviewsHtml;

// Close connection
$stmt->close();
$conn->close();


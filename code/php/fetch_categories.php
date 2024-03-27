<?php   
// database connection logic 
include 'db_config.php';

$sql = "SELECT cid, cname FROM category";
$result = $conn->query($sql);

$categories = array();
while($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories); 

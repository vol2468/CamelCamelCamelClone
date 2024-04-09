<?php
// aim : have 2d array where data[0] contains date and data[1] contains price.

// post[uid] holds uid
if (isset($_POST['pid']) && !empty($_POST['pid'])){
    $pid = $_POST['pid'];
} else {
    $output = "<p>No pid provided</p>";
    exit($output);
}

try {
    // database connection
    include 'connect.php';

    if ($error != null) {
        $output = "<p>Unable to connect to database!</p>";
        exit($output);
    } else {

        $dateHistory = array();
        $priceHistory = array();
        $sql = 'SELECT date, price FROM priceHistory WHERE pid = ? ORDER BY date'; 
        $statement = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($statement, "i", $pid);
        mysqli_stmt_execute($statement);
        mysqli_stmt_store_result($statement);
        if (mysqli_stmt_num_rows($statement) > 0) {
            mysqli_stmt_bind_result($statement, $date, $price);
            for ($i=0; mysqli_stmt_fetch($statement); $i++) { 
                array_push($dateHistory, $date);
                array_push($priceHistory, $price);
            }
        } else {
            // Price history does not exist for this product
        }

        $data = array($dateHistory, $priceHistory);
        echo json_encode($data);

    }

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
} finally {
    mysqli_close($connection);
}


?>
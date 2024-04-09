<?php
try {
    // database connection
    include 'connect.php';

    if($error != null)
    {
        $output = "<p>Unable to connect to database!</p>";
        exit($output);
    } 
    else {

        // Retrieve popular items in past twenty days
        // Retrieve popular items pid
        if (isset($_SESSION['popularItemNum'])) {
            $popularItemNum = $_SESSION['popularItemNum'];
            $popularItems = array();
            $popularItemNames = array();
            $popularItemPrices = array();
            $popularItemImages = array();
            date_default_timezone_set('America/Vancouver');
            $currentDateTime = date('Y-m-d H:i:s');
            $twentyDaysAgo = date('Y-m-d H:i:s', strtotime('-20 days', strtotime($currentDateTime)));
            $sql = 'SELECT pid, count(pid) as visitCount FROM visitHistory WHERE date > ? GROUP BY pid ORDER BY visitCount DESC LIMIT ?';
            $statement = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($statement, "si", $twentyDaysAgo, $popularItemNum);
            mysqli_stmt_execute($statement);
            mysqli_stmt_store_result($statement);
            if (mysqli_stmt_num_rows($statement) > 0) {
                mysqli_stmt_bind_result($statement, $pid, $visitCount);
                for ($i=0; mysqli_stmt_fetch($statement); $i++) { 
                    $popularItems[$i] = $pid;
                }
                for ($i= 0; $i < count($popularItems); $i++) {
                    $pid = $popularItems[$i];
                    // Retrieve pname and imgid
                    $sql ='SELECT pname, imgid FROM product WHERE pid = ?';
                    $statement = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($statement, "s", $pid);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);
                    mysqli_stmt_bind_result($statement, $pname, $imgid);
                    mysqli_stmt_fetch($statement);
                    $popularItemNames[$i] = $pname;
    
                    // Retrieve price
                    $sql = "SELECT price, date FROM priceHistory WHERE pid = ? ORDER BY date DESC";
                    $statement = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($statement, "i", $pid);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);
                    if (mysqli_stmt_num_rows($statement) > 0) {
                        mysqli_stmt_bind_result($statement, $price, $date);
                        mysqli_stmt_fetch($statement);
                        $popularItemPrices[$i] = $price;
                    } else {
                        $price = 0;
                    }
    
                    // Retrieve image file
                    $sql = "SELECT file FROM image WHERE imgid = ?";
                    $statement = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($statement, "i", $imgid);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);
                    mysqli_stmt_bind_result($statement, $file);
                    mysqli_stmt_fetch($statement);
                    $popularItemImages[$i] = $file;
                }
            } else {
                echo "No recent visit history. ";
            }
        } else {
            echo 'Number of popular items to be fetched is not specified. ';
        }
    }

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
} finally {
    mysqli_close($connection);
}


?>
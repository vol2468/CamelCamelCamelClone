<?php
try {
    // database connection
    include 'connect.php';

    if ($error != null) {
        $output = "<p>Unable to connect to database!</p>";
        exit($output);
    } else {
        // Retrieve number of watch list items for the logged in user
        // Retrieve watch list item pid
        $priceWatchItems = array();
        $priceWatchItemNames = array();
        $priceWatchItemPrices = array();
        $priceWatchItemImages = array();
        $sql = 'SELECT pid FROM watchList 
                WHERE watchList.uid = ?';
        $statement = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($statement, "i", $uid);
        mysqli_stmt_execute($statement);
        mysqli_stmt_store_result($statement);
        
        if (mysqli_stmt_num_rows($statement) > 0) {
            mysqli_stmt_bind_result($statement, $pid);
            for ($i = 0; mysqli_stmt_fetch($statement); $i++) {
                $priceWatchItems[$i] = $pid;
            }
            for ($i = 0; $i < count($priceWatchItems); $i++) {
                $pid = $priceWatchItems[$i];
                // Retrieve pname and imgid
                $sql = 'SELECT pname, imgid FROM product WHERE pid = ?';
                $statement = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($statement, "s", $pid);
                mysqli_stmt_execute($statement);
                mysqli_stmt_store_result($statement);
                mysqli_stmt_bind_result($statement, $pname, $imgid);
                mysqli_stmt_fetch($statement);
                $priceWatchItemNames[$i] = $pname;

                // Retrieve price
                $sql = "SELECT price, date FROM priceHistory WHERE pid = ? ORDER BY date DESC";
                $statement = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($statement, "i", $pid);
                mysqli_stmt_execute($statement);
                mysqli_stmt_store_result($statement);
                if (mysqli_stmt_num_rows($statement) > 0) {
                    mysqli_stmt_bind_result($statement, $price, $date);
                    mysqli_stmt_fetch($statement);
                    $priceWatchItemPrices[$i] = $price;
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
                $priceWatchItemImages[$i] = $file;
            }
        } else {
            echo "No items in your price watches. ";
        }
    }

} catch (Exception $e) {
    echo 'Error Message: ' . $e->getMessage();
} finally {
    mysqli_close($connection);
}


?>
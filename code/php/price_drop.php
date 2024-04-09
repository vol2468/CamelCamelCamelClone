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

        // Retrieve price dropped items in past twenty days
        // Compare the current price with the maximum price within last 20 days. 

        if(isset($_SESSION['priceDropNum'])){
            $priceDropNum = $_SESSION['priceDropNum'];

            $pidList = array(); 
            $currentPrices = array(); 
            $priceDifferences = array();
            $recentMaxPrices = array();
            $priceDropItems = array(); 
            $priceDropNames = array(); 
            $priceDropPrices = array();
            $priceDropDifferences = array();
            $priceDropImages = array();

            date_default_timezone_set('America/Vancouver');
            $currentDateTime = date('Y-m-d H:i:s');
            $twentyDaysAgo = date('Y-m-d H:i:s', strtotime('-20 days', strtotime($currentDateTime)));
    
            // Retrieve the most recent price for every item
            $sql = 'SELECT priceHistory.pid, priceHistory.price, date '.
                    'FROM priceHistory '.
                    'JOIN (SELECT pid, max(date) AS max_date FROM priceHistory GROUP BY pid) AS priceRecent '.
                    'ON priceHistory.pid = priceRecent.pid '.
                    'WHERE priceHistory.date = priceRecent.max_date';
            $statement = mysqli_prepare($connection, $sql);
            mysqli_stmt_execute($statement);
            mysqli_stmt_store_result($statement);
            if (mysqli_stmt_num_rows($statement) > 0) {
                mysqli_stmt_bind_result($statement, $pid, $currentPrice, $date);
                for ($i=0; mysqli_stmt_fetch($statement); $i++) { 
                    $pidList[$i] = $pid;
                    $currentPrices[$pid] = $currentPrice;
                }
    
                // Retrieve max price within 20 days. 
                $sql = 'SELECT pid, max(price) AS maxPrice FROM priceHistory WHERE date > ? GROUP BY pid';
                $statement = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($statement, "s", $twentyDaysAgo);
                mysqli_stmt_execute($statement);
                mysqli_stmt_store_result($statement);
                mysqli_stmt_bind_result($statement, $pid, $maxPrice);
                while (mysqli_stmt_fetch($statement)) {
                    $priceDifferences[$pid] = round($maxPrice - $currentPrices[$pid], 2);
                }
                arsort($priceDifferences);
                $topPriceDrops = array_slice($priceDifferences, 0, $priceDropNum, true); // pick up top n price drop
                
                $counter = 0;
                
                foreach ($topPriceDrops as $pid => $priceDifference) {
                    $priceDropItems[$counter] = $pid;
                    $priceDropDifferences[$counter] = $priceDifference;
                    $priceDropPrices[$counter] = $currentPrices[$pid];
    
                    // Retrieve product name and image file
                    $sql = "SELECT pname, imgid FROM product WHERE pid = ?";
                    if ($statement = mysqli_prepare($connection, $sql)) {
                        mysqli_stmt_bind_param($statement, "i", $pid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        mysqli_stmt_bind_result($statement, $pname, $imgid);
                        mysqli_stmt_fetch($statement);
                        $priceDropNames[$counter] = $pname;
    
                        // Retrieve image
                        $sql = "SELECT file FROM image WHERE imgid = ?";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $imgid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        mysqli_stmt_bind_result($statement, $file);
                        mysqli_stmt_fetch($statement);
                        $priceDropImages[$counter] = $file;   
                    }
                    $counter++;
                }

    
            } else {
                echo "No record in past twenty days.  ";
            }
        } else {
            echo 'Number of price dropped items to be fetched is not specified. ';
        }
    }

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
} finally {
    mysqli_close($connection);
}


?>
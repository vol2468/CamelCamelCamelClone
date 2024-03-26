<!DOCTYPE html>

<html>
    <head lang="en">
        <meta charset="utf-8">
        <title>Camelcamelcamel Clone</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/main.css"/>
        <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <header>
            <?php 
                include_once("header.php"); 
            ?>
        </header>

        <main>
            <?php 
                // Fetch uid 
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["uid"]) && !empty($_POST["uid"])) {
                        $uid = $_POST["uid"];
                    }
                }
                try {
                    // database connection
                    $host = "localhost";
                    $database = "project";
                    $user = "root";
                    $password = "";
                    $connection = mysqli_connect($host, $user, $password, $database);

                    $error = mysqli_connect_error();

                    if($error != null)
                    {
                        $output = "<p>Unable to connect to database!</p>";
                        exit($output);
                    } 
                    else {

                        // Retrieve popular items in past twenty days

                        // Retrieve popular items pid
                        $popularItems = array();
                        $popularItemNames = array();
                        $popularItemPrices = array();
                        $popularItemImages = array();
                        date_default_timezone_set('America/Vancouver');
                        $currentDateTime = date('Y-m-d H:i:s');
                        $twentyDaysAgo = date('Y-m-d H:i:s', strtotime('-20 days', strtotime($currentDateTime)));
                        $sql = 'SELECT pid, count(pid) as visitCount FROM visitHistory WHERE date > ? GROUP BY pid ORDER BY visitCount DESC LIMIT 4';
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "s", $twentyDaysAgo);
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

                        // Retrieve price dropped items in past twenty days
                        // Compare the current price with the maximum price within last 20 days. 
                        $pidList = array(); 
                        $currentPrices = array(); 
                        $priceDifferences = array();
                        $recentMaxPrices = array();
                        $priceDropItems = array(); 
                        $priceDropNames = array(); 
                        $priceDropPrices = array();
                        $priceDropDifferences = array();
                        $priceDropImages = array();

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
                                $priceDifferences[$pid] = $maxPrice - $currentPrices[$pid];
                            }
                            arsort($priceDifferences);
                            $topPriceDrops = array_slice($priceDifferences, 0, 4, true); // pick up top 4 price drop
                            
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
                    }
                
                } catch (Exception $e) {
                    echo 'Error Message: ' .$e->getMessage();
                } finally {
                    mysqli_close($connection);
                }
            
            
            
            ?>
            <div id="popular-drop">
                <div id="popular">
                    <h1>Popular Products</h1>
                    <div class="products">
                        <?php
                            // printout popular item cards
                            for ($i=0; $i < count($popularItems); $i++) { 
                                echo '<div class="card">';
                                echo '<a href="product.php?pid='.$priceDropItems[$i].'"><img src="data:image/jpg;base64,'.base64_encode($popularItemImages[$i]).'" style="width: 100%;"/></a>';
                                echo '<h3>'.$popularItemNames[$i].'</h3>';
                                echo '<p class="price">$'.$popularItemPrices[$i].'</p>';
                                echo '<p><button><a href="product.php?pid='.$popularItems[$i].'">See Product Detail</a></button></p>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
                <div id="drop">
                    <h1>Top Price Drops</h1>
                    <div class="products">
                        <?php
                            // printout top price dropped item cards
                            for ($i=0; $i < count($priceDropItems); $i++) { 
                                echo '<div class="card">';
                                echo '<a href="product.php?pid='.$priceDropItems[$i].'"><img src="data:image/jpg;base64,'.base64_encode($priceDropImages[$i]).'" style="width: 100%;"/></a>';
                                echo '<h3>'.$priceDropNames[$i].'</h3>';
                                echo '<p class="price">$'.$priceDropPrices[$i].'</p>';
                                echo 'Price drop: <p class="price">$'.$priceDropDifferences[$i].'</p>';
                                echo '<p><button><a href="product.php?pid='.$priceDropItems[$i].'">See Product Detail</a></button></p>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <?php
                include_once("footer.php"); 
            ?>
        </footer>

    </body>
</html>
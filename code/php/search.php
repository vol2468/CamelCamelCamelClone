<!DOCTYPE html>

<?php

session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}

?>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <title>Camelcamelcamel Clone</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/search.css"/>
        <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="../js/checkpricewatchupdates.js"></script>
    </head>
    <body>

        <header>
            <?php 
                include_once("header.php"); 
            ?>
        </header>

        <main>
            <?php
            include ("breadcrumb.php");
            
            // using try catch statement to handle any error
            try {
                // database connection
                include "connect.php";

                if ($error != null) {
                    $output = "<p>Unable to connect to database!</p>";
                    exit($output);
                } else {
                    // obtain the search result when category & search text are specified
                    if (isset($_GET["categoryId"]) && !empty($_GET["categoryId"]) && $_GET["categoryId"] !== 0 && isset($_GET["searchText"]) && !empty($_GET["searchText"])) {
                        $cid = $_GET["categoryId"];
                        $searchText = $_GET["searchText"];

                        // list of product name contains "searchText" from selected category
                        $sql = "SELECT cname, p.pid, pname, price, file ".
                               "FROM product p ".
                               "JOIN priceHistory ph ON p.pid = ph.pid ".
                               "JOIN category c ON p.cid = c.cid ".
                               "JOIN image i ON p.imgid = i.imgid ".
                               "WHERE c.cid = ? ".
                               "AND p.pname LIKE ? ".
                               "AND ph.date = (SELECT MAX(date) FROM priceHistory WHERE pid = p.pid)";

                        if ($statement = mysqli_prepare($connection, $sql)) {
                            $search = "%$searchText%";
                            mysqli_stmt_bind_param($statement, "is", $cid, $search);
                            mysqli_stmt_execute($statement);
                            mysqli_stmt_store_result($statement);

                            if (mysqli_stmt_num_rows($statement) < 1) {
                                $noresult = "<p>Unfortunately, we couldn't find any...<p>";
                            } else {
                                // fetch and display the result
                                mysqli_stmt_bind_result($statement, $cname, $pid, $pname, $price, $file);

                                $counter = 0;
                                while (mysqli_stmt_fetch($statement)) {
                                    $productCategory[$counter] = $cname;
                                    $productId[$counter] = $pid;
                                    $productName[$counter] = $pname;
                                    $productPrice[$counter] = $price;
                                    $productImage[$counter] = $file;
                                    $counter++;
                                }
                            }

                        } else {
                            echo "Failed to prepare statement";
                        }

                        // close the statement and connection
                        mysqli_stmt_close($statement);
                        mysqli_close($connection);

                    // obtain the search result when search text is specified but not category
                    } elseif (isset($_GET["categoryId"]) && empty($_GET["categoryId"]) && isset($_GET["searchText"]) && !empty($_GET["searchText"])) {
                        $searchText = $_GET["searchText"];

                        // list of product name contains "searchText" from all category
                        $sql = "SELECT cname, p.pid, pname, price, file ".
                               "FROM product p ".
                               "JOIN priceHistory ph ON p.pid = ph.pid ".
                               "JOIN category c ON p.cid = c.cid ".
                               "JOIN image i ON p.imgid = i.imgid ".
                               "AND p.pname LIKE ? ".
                               "AND ph.date = (SELECT MAX(date) FROM priceHistory WHERE pid = p.pid)";

                        if ($statement = mysqli_prepare($connection, $sql)) {
                            $search = "%$searchText%";
                            mysqli_stmt_bind_param($statement, "s", $search);
                            mysqli_stmt_execute($statement);
                            mysqli_stmt_store_result($statement);

                            if (mysqli_stmt_num_rows($statement) < 1) {
                                $noresult = "<p>Unfortunately, we couldn't find any...<p>";
                            } else {
                                // fetch and display the result
                                mysqli_stmt_bind_result($statement, $cname, $pid, $pname, $price, $file);

                                $counter = 0;
                                while (mysqli_stmt_fetch($statement)) {
                                    $productCategory[$counter] = $cname;
                                    $productId[$counter] = $pid;
                                    $productName[$counter] = $pname;
                                    $productPrice[$counter] = $price;
                                    $productImage[$counter] = $file;
                                    $counter++;
                                }

                            }

                        } else {
                            echo "Failed to prepare statement";
                        }

                        // close the statement and connection
                        mysqli_stmt_close($statement);
                        mysqli_close($connection);

                    // obtain the search result when category is specified but not search text
                    } elseif (isset($_GET["categoryId"]) && !empty($_GET["categoryId"]) && (!isset($_GET["searchText"]) || empty($_GET["searchText"]))) {
                        $cid = $_GET["categoryId"];

                        // list of all product in selected category
                        $sql = "SELECT cname, p.pid, pname, price, file ".
                               "FROM product p ".
                               "JOIN priceHistory ph ON p.pid = ph.pid ".
                               "JOIN category c ON p.cid = c.cid ".
                               "JOIN image i ON p.imgid = i.imgid ".
                               "WHERE c.cid = ? ".
                               "AND ph.date = (SELECT MAX(date) FROM priceHistory WHERE pid = p.pid)";

                        if ($statement = mysqli_prepare($connection, $sql)) {
                            mysqli_stmt_bind_param($statement, "i", $cid);
                            mysqli_stmt_execute($statement);
                            mysqli_stmt_store_result($statement);

                            if (mysqli_stmt_num_rows($statement) < 1) {
                                $noresult = "<p>Unfortunately, we couldn't find any...<p>";
                            } else {
                                // fetch and display the result
                                mysqli_stmt_bind_result($statement, $cname, $pid, $pname, $price, $file);

                                $counter = 0;
                                while (mysqli_stmt_fetch($statement)) {
                                    $productCategory[$counter] = $cname;
                                    $productId[$counter] = $pid;
                                    $productName[$counter] = $pname;
                                    $productPrice[$counter] = $price;
                                    $productImage[$counter] = $file;
                                    $counter++;
                                }

                            }

                        } else {
                            echo "Failed to prepare statement";
                        }

                        // close the statement and connection
                        mysqli_stmt_close($statement);
                        mysqli_close($connection);
                    } else {
                        // list of product in all category
                        $sql = "SELECT cname, p.pid, pname, price, file ".
                               "FROM product p ".
                               "JOIN priceHistory ph ON p.pid = ph.pid ".
                               "JOIN category c ON p.cid = c.cid ".
                               "JOIN image i ON p.imgid = i.imgid ".
                               "AND ph.date = (SELECT MAX(date) FROM priceHistory WHERE pid = p.pid)";

                        if ($statement = mysqli_prepare($connection, $sql)) {
                            mysqli_stmt_execute($statement);
                            mysqli_stmt_store_result($statement);

                            if (mysqli_stmt_num_rows($statement) < 1) {
                                $noresult = "<p>Unfortunately, we couldn't find any...<p>";
                            } else {
                                // fetch and display the result
                                mysqli_stmt_bind_result($statement, $cname, $pid, $pname, $price, $file);

                                $counter = 0;
                                while (mysqli_stmt_fetch($statement)) {
                                    $productCategory[$counter] = $cname;
                                    $productId[$counter] = $pid;
                                    $productName[$counter] = $pname;
                                    $productPrice[$counter] = $price;
                                    $productImage[$counter] = $file;
                                    $counter++;
                                }

                            }

                        } else {
                            echo "Failed to prepare statement";
                        }

                        // close the statement and connection
                        mysqli_stmt_close($statement);
                        mysqli_close($connection);
                    }

                }

            } catch (Exception $e) {
                echo 'Error Message: ' . $e->getMessage();
            }

            ?>
            <h1>Search result for "<?php echo $searchText; ?>"</h1>
            <div id="products">
                <?php
                    if (!isset($noresult)) {
                        // display each item
                        for ($i = 0; $i < count($productName); $i++) { 
                            echo "<div class='items'>";
                            echo "<div class='image'>";
                            echo '<img class="prod-img" src="data:image/jpg;base64,'.base64_encode($productImage[$i]).'"/>';
                            echo "</div>";
                            echo "<div class='desc'>";
                            echo "<h2>".$productName[$i]."</h2>";
                            echo "<p class='category'>Category: ".$productCategory[$i]."</p>";
                            echo "<p class='price'>$".$productPrice[$i]."</p>";
                            echo "<button class='prod-details'><a href='product.php?pid=".$productId[$i]."'>See Product Detail</a></button>";
                            echo "</div>";
                            echo "</div>";
                            if ($i !== count($productName)-1)
                                echo "<hr>";
                        }
                    } else {
                        echo "<p id='noresult'>$noresult</p>";
                    }
                ?>
            </div>
        </main>

        <footer>
            <?php
                include_once("footer.php"); 
            ?>
        </footer>

    </body>
</html>
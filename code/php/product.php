<!DOCTYPE html>
<?php

session_start();

if (!isset($_SESSION["uid"])) {
    $_SESSION["error"] = "You need to sign in to view product detail.";
    header("Location: login.php");
} else {
    $uid = $_SESSION["uid"];
}

?>

<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Product</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/product.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="../js/review.js"></script>
    <script src="../js/filter.js"></script>
    <script src="../js/product.js"></script>

</head>

<body>
    <header>
        <?php
        include_once ("header.php");
        ?>
    </header>

    <main>
        <?php
        include ("breadcrumb.php");
        try {
            // database connection
            include "connect.php";

            if ($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } elseif (!isset($_GET["pid"])) {
                $output = "<p>Unable to fetch pid!</p>";
                exit($output);
            } else {
                // Retrieve pid from GET
                $pid = $_GET["pid"];

                $currentDate = date('Y-m-d');

                // Store product visit information in db.
                $sql = "SELECT * FROM visitHistory WHERE uid = ? AND pid = ? AND date = ?";
                $statement = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($statement, "iis", $uid, $pid, $currentDate);
                mysqli_stmt_execute($statement);
                mysqli_stmt_store_result($statement);
                if (mysqli_stmt_num_rows($statement) == 0) {
                    $sql = "INSERT INTO visitHistory VALUES (?, ?, ?)";
                    $statement = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($statement, "iis", $uid, $pid, $currentDate);
                    mysqli_stmt_execute($statement);
                }

                // Retrieve product information
                $sql = "SELECT pname, cid, imgid FROM product WHERE pid = ?";
                $statement = mysqli_prepare($connection, $sql);
                if ($statement = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($statement, "i", $pid);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);

                    if (mysqli_stmt_num_rows($statement) > 0) {
                        mysqli_stmt_bind_result($statement, $pname, $cid, $imgid);
                        mysqli_stmt_fetch($statement);

                        // Retrieve image
                        $sql = "SELECT file FROM image WHERE imgid = ?";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $imgid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        mysqli_stmt_bind_result($statement, $file);
                        mysqli_stmt_fetch($statement);

                        // Retrieve rate
                        $sql = "SELECT AVG(rate) FROM review GROUP BY pid HAVING pid = ?";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $pid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        if (mysqli_stmt_num_rows($statement) > 0) {
                            mysqli_stmt_bind_result($statement, $rate);
                            mysqli_stmt_fetch($statement);
                        } else {
                            $rate = 0;
                        }
                        $rate = round($rate, 2);

                        // Retrieve price
                        $sql = "SELECT price, date, AVG(price) FROM priceHistory WHERE pid = ? ORDER BY date DESC";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $pid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        if (mysqli_stmt_num_rows($statement) > 0) {
                            mysqli_stmt_bind_result($statement, $price, $date, $avgprice);
                            mysqli_stmt_fetch($statement);
                        } else {
                            $price = 0;
                        }
                        $avgprice = round($avgprice, 2);

                        // Retrieve price
                        $sql = "SELECT price, date FROM priceHistory WHERE pid = ? ORDER BY date DESC";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $pid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        if (mysqli_stmt_num_rows($statement) > 0) {
                            mysqli_stmt_bind_result($statement, $price, $date);
                            mysqli_stmt_fetch($statement);
                        } else {
                            $price = 0;
                        }

                        // Retrieve category
                        $sql = "SELECT cname FROM category WHERE cid = ?";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $cid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        mysqli_stmt_bind_result($statement, $cname);
                        mysqli_stmt_fetch($statement);

                        // Retrieve link to Amazon page
                        $sql = "SELECT amazonlink FROM product WHERE pid = ?";
                        $statement = mysqli_prepare($connection, $sql);
                        mysqli_stmt_bind_param($statement, "i", $pid);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);
                        mysqli_stmt_bind_result($statement, $amazonlink);
                        mysqli_stmt_fetch($statement);

                    } else {    // invalid credential
                        $_SESSION["error"] = "No such product. ";
                        header("Location: main.php?error=invalid1");
                        exit();
                    }
                }

            }


        } catch (Exception $e) {
            echo 'Error Message: ' . $e->getMessage();
        } finally {
            mysqli_close($connection);
        }
        ?>
        <!-- to make $uid and $pid accessible in review.js -->
        <script>
            var uid = <?php echo json_encode($uid); ?>;
            var pid = <?php echo json_encode($pid); ?>;
        </script>

        <div id="product">
            <img id="pimg" <?php echo 'src="data:image/jpg;base64,' . base64_encode($file) . '"'; ?>>
            <div id="prod-info">
                <h1 id="pname">
                    <?php echo $pname; ?>
                </h1>
                <p id="category">Category:
                    <?php echo $cname; ?>
                </p>
                <p id="rate">
                    <?php echo $rate; ?>/<span>5</span>
                </p>
                <p id="price">$
                    <?php echo $price; ?>
                </p>
                <p id="avg-price">Average price:
                    <?php echo $avgprice; ?>
                </p>
                <p id="amapra">Amazon Price</p>
                <p id="date-time">as of
                    <?php echo $date; ?>
                </p>
                <div id="amazon">
                    <button id="view-amazon"><a href="
                    <?php echo $amazonlink; ?>
                    ">View on Amazon</a></button>
                </div>
            </div>
        </div>
        <div id="reviews">
            <h2>Reviews</h2>
            <p>Filter by: </p>
            <select id="rating-filter">
                <option value="0" selected="selected">All Ratings</option>
                <option value="1">★</option>
                <option value="2">★★</option>
                <option value="3">★★★</option>
                <option value="4">★★★★</option>
                <option value="5">★★★★★</option>
            </select>
            <div class="review-column">
                <div id="reviews-container"></div>
            </div>
            <div id="reviews">
                <h3>Write a review</h3>
                <form id="add-review" method="post">
                    <input type="text" id="comment" name="comment" placeholder="Add a review...">
                    <input type="number" id="rating" name="rating" min="0" max="5" placeholder="Rate" /><br /><br />
                    <button type="submit" id="submit">Submit Review</button>
                </form>
            </div>
        </div>
        <div id="price-analytics">
            <canvas id="price-history" data-pid="<?php echo $pid; ?>"></canvas>
        </div>
    </main>

    <footer>
        <?php
        include_once ("footer.php");
        ?>
    </footer>

</body>

</html>
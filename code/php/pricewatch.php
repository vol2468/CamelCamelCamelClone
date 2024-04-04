<!DOCTYPE html>

<?php
session_start();

if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
} else {
    $uid = $_SESSION["uid"];
    $usertype = $_SESSION["usertype"];
}

if (isset($_SESSION["status"])) {
    $status = $_SESSION["status"];
}

if (isset($_SESSION["chpic"])) {
    $chpic = $_SESSION["chpic"];
}

?>

<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Your Account</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/pricewatch.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        ?>
        <h1>Your Account</h1>
        <?php

        // using try catch statement to handle any error
        try {
            // database connection
            include "connect.php";

            if ($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } else {
                // check if the username is valid using a prepared statement
                $sql = "SELECT * FROM user WHERE uid = ?";
                if ($statement = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($statement, "i", $uid);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);

                    if (mysqli_stmt_num_rows($statement) < 1) {
                        echo "<p>Invalid uid<p>";
                    } else {
                        // fetch and display the result
                        mysqli_stmt_bind_result($statement, $uid, $uname, $email, $passwd, $imgid, $usertype);

                        mysqli_stmt_fetch($statement);

                        // retrive image from the database
                        $sql = "SELECT file FROM image where imgid = ?";
                        // build the prepared statement SELECTing on the userID for the user
                        $stmt = mysqli_stmt_init($connection);
                        //init prepared statement object
                        mysqli_stmt_prepare($stmt, $sql);
                        // bind the query to the statement
                        mysqli_stmt_bind_param($stmt, "i", $imgid);
                        // bind in the variable data (ie userID)
                        $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
                        // Run the query. run spot run!
                        mysqli_stmt_bind_result($stmt, $image); //bind in results
                        // Binds the columns in the resultset to variables
                        mysqli_stmt_fetch($stmt);
                        // Fetches the blob and places it in the variable $image for use as well
                        // as the image type (which is stored in $type)
                        mysqli_stmt_close($stmt);
                        // release the statement
                        // echo '<img src="data:image/jpeg;base64,'.base64_encode($image).'"/>';
                    }

                } else {
                    echo "Failed to prepare statement";
                }

                // close the statement and connection
                mysqli_stmt_close($statement);
                mysqli_close($connection);
            }

        } catch (Exception $e) {
            echo 'Error Message: ' . $e->getMessage();
        }

        ?>
        <div id="menu-bar">
            <a href="account.php">Account Profile</a>
            <a href="pricewatch.php">Your Price Watches</a>
            <?php
                if ($usertype === 1) {
                    echo "<a href='dashboard.php'>Dashboard</a>";
                    echo "<a href='#'>Products</a>";
                    echo "<a href='users.php'>Users</a>";
                    echo "<a href='#'>Tickets</a>";
                }
            ?>
            <a href="logout.php" id="logout">Sign out</a>
        </div>
        <div id="price-watch">
            <h2>Your Price Watches</h2>
            <div id="products">
                <!-- 3 cards / row -->
                <div class="row">
                    <div class="card">
                        <a href="product.php?pid=2"><img src="../images/sock.png"/></a>
                        <h3>Balega Silver Compression Fit Performance No Show Athletic Running Socks for Men and Women (1 Pair), White/ Grey, X-Large</h3>
                        <p class="price">$12.76</p>
                        <p><button><a href="product.php?pid=2">See Product Detail</a></button></p>
                    </div>
                    <div class="card">
                        <a href="product.php?pid=2"><img src="../images/sock.png"/></a>
                        <h3>Balega Silver Compression Fit Performance No Show Athletic Running Socks for Men and Women (1 Pair), White/ Grey, X-Large</h3>
                        <p class="price">$12.76</p>
                        <p><button><a href="product.php?pid=2">See Product Detail</a></button></p>
                    </div>
                    <div class="card">
                        <a href="product.php?pid=2"><img src="../images/sock.png"/></a>
                        <h3>Balega Silver Compression Fit Performance No Show Athletic Running Socks for Men and Women (1 Pair), White/ Grey, X-Large</h3>
                        <p class="price">$12.76</p>
                        <p><button><a href="product.php?pid=2">See Product Detail</a></button></p>
                    </div>
                </div>
                <div class="row">
                    <div class="card">
                        <a href="product.php?pid=2"><img src="../images/sock.png"/></a>
                        <h3>Balega Silver Compression Fit Performance No Show Athletic Running Socks for Men and Women (1 Pair), White/ Grey, X-Large</h3>
                        <p class="price">$12.76</p>
                        <p><button><a href="product.php?pid=2">See Product Detail</a></button></p>
                    </div>
                    <div class="card">
                        <a href="product.php?pid=2"><img src="../images/sock.png"/></a>
                        <h3>Balega Silver Compression Fit Performance No Show Athletic Running Socks for Men and Women (1 Pair), White/ Grey, X-Large</h3>
                        <p class="price">$12.76</p>
                        <p><button><a href="product.php?pid=2">See Product Detail</a></button></p>
                    </div>
                    <div class="card">
                        <a href="product.php?pid=2"><img src="../images/sock.png"/></a>
                        <h3>Balega Silver Compression Fit Performance No Show Athletic Running Socks for Men and Women (1 Pair), White/ Grey, X-Large</h3>
                        <p class="price">$12.76</p>
                        <p><button><a href="product.php?pid=2">See Product Detail</a></button></p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer>
        <?php
        include_once ("footer.php");
        ?>
    </footer>

</body>

</html>
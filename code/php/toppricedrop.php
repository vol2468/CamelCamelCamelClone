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
    <title>Top Price Drops</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/pop-drop.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/checkpricewatchupdates.js"></script>
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
        <h1>Top Price Drops</h1>
        <?php

        // using try catch statement to handle any error
        try {
            // database connection
            include "connect.php";

            if ($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } else {
                // MODIFY HERE TO RETRIEVE POPULAR PRODUCT DATA
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

        $_SESSION['priceDropNum'] = 8;
        include 'price_drop.php';


        ?>
        <div id="products">
            <?php
            $columnCounter = 0;
            $rowMaxItems = 4;
            // printout top price dropped item cards
            for ($i = 0; $i < count($priceDropItems); $i++) {
                if ($columnCounter % $rowMaxItems == 0) {
                    // Open new row
                    echo '<div class="row">';
                }
                echo '<div class="card">';
                echo '<a href="product.php?pid=' . $priceDropItems[$i] . '"><img src="data:image/jpg;base64,' . base64_encode($priceDropImages[$i]) . '" style="width: 100%;"/></a>';
                echo '<h3>' . $priceDropNames[$i] . '</h3>';
                echo '<p class="price">$' . $priceDropPrices[$i] . '</p>';
                // echo '<h3 class="price-drop">Price drop: $'.$priceDropDifferences[$i].'</h3>';
                echo '<p><button><a href="product.php?pid=' . $priceDropItems[$i] . '">See Product Detail</a></button></p>';
                echo '</div>';
                if ($columnCounter % $rowMaxItems == $rowMaxItems - 1) {
                    // Open new row
                    echo '</div>';
                }
                $columnCounter++;
            }
            ?>
        </div>

    </main>

    <footer>
        <?php
        include_once ("footer.php");
        ?>
    </footer>

</body>

</html>
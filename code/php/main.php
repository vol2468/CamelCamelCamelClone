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
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/checkpricewatchupdates.js"></script>
</head>

<body>
    <?php
    echo "<div id=header-container>";
    include_once ("events_bar.php");
    ?>
    <header>
        <?php
        include_once ("header.php");

        ?>
    </header>
    <?php
    echo "</div>";
    ?>

    <main>
        <?php
        $_SESSION['popularItemNum'] = 10;
        include 'popular_item.php';
        $_SESSION['priceDropNum'] = 8;
        include 'price_drop.php';
        ?>
        <div id="popular-drop">
            <div id="popular">
                <h1>Popular Products</h1>
                <div class="products">
                    <?php
                    // printout popular item cards
                    for ($i = 0; $i < count($popularItems); $i++) {
                        echo '<div class="card">';
                        echo '<a href="product.php?pid=' . $priceDropItems[$i] . '"><img src="data:image/jpg;base64,' . base64_encode($popularItemImages[$i]) . '" style="width: 100%;"/></a>';
                        echo '<h3>' . $popularItemNames[$i] . '</h3>';
                        echo '<p class="price">$' . $popularItemPrices[$i] . '</p>';
                        echo '<p><button><a href="product.php?pid=' . $popularItems[$i] . '">See Product Detail</a></button></p>';
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
                    for ($i = 0; $i < count($priceDropItems); $i++) {
                        echo '<div class="card">';
                        echo '<a href="product.php?pid=' . $priceDropItems[$i] . '"><img src="data:image/jpg;base64,' . base64_encode($priceDropImages[$i]) . '" style="width: 100%;"/></a>';
                        echo '<h3>' . $priceDropNames[$i] . '</h3>';
                        echo '<p class="price">$' . $priceDropPrices[$i] . '</p>';
                        echo '<h3 class="price-drop">Price drop: $' . $priceDropDifferences[$i] . '</h3>';
                        echo '<p><button><a href="product.php?pid=' . $priceDropItems[$i] . '">See Product Detail</a></button></p>';
                        echo '</div>';
                    }
                    ?>
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
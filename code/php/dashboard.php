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

?>

<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Your Account</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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
        <h1>Dashboard</h1>
        <?php

        ?>
        <div id="menu-bar">
            <a href="account.php">Account Profile</a>
            <a href="pricewatch.php">Your Price Watches</a>
            <?php
                if ($usertype === 1) {
                    echo "<a href='dashboard.php'>Dashboard</a>";
                    echo "<a href=''>Products</a>";
                    echo "<a href='users.php'>Users</a>";
                    echo "<a href=''>Tickets</a>";
                }
            ?>
            <a href="logout.php" id="logout">Sign out</a>
        </div>
        <div id="dashboard">
            <a href="users.php">
                <div class="box user-box">
                    <i class="fas fa-users"></i>
                    <p class="legend">Total Users</p>
                    <p class="count" id="user-count">/p>
                </div>
            </a>

            <div class="box ticket-box">
                <i class="fas fa-ticket-alt"></i>
                <p class="legend">Unresolved Tickets</p>
                <p class="count" id="ticket-count">3</p>
            </div>

            <!-- REPLACE WITH ACTUAL DATA -->
            <div>
                <canvas id="traffic"></canvas>
            </div>
            <script>

                const traffic = document.getElementById("traffic");
                const labels = ["January", "February", "March", "April", "May", "June", "July"];
                
                var chart = new Chart(traffic, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Website Traffic",
                            data: [65, 59, 80, 81, 56, 55, 40],
                            fill: false,
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    }
                })

            </script>
        </div>

    </main>

    <footer>
        <?php
        include_once ("footer.php");
        ?>
    </footer>

</body>

</html>
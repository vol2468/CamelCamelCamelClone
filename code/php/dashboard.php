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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="../js/dashboard.js"></script>
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
                    include_once ("admin_sidebar.php");
                }
            ?>
            <a href="logout.php" id="logout">Sign out</a>
        </div>
        <div id="dashboard">
            <div id="card">
                <a href="users.php" id="numu-of-user">
                    <div class="box user-box">
                        <i class="fas fa-users"></i>
                        <p class="legend">Total Users</p>
                        <p class="count" id="user-count"></p>
                    </div>
                </a>

                <a href="tickets.php" id="numu-of-tickets">
                    <div class="box ticket-box">
                        <i class="fas fa-ticket-alt"></i>
                        <p class="legend">Unresolved Tickets</p>
                        <p class="count" id="ticket-count"></p>
                    </div>
                </a>
            </div>
            <div id="analytics">
                <!-- TODO: Make this selector dynamic -->
                <select id="yearSelector">
                    <option value="2023">2023</option>
                    <option value="2024" selected>2024</option>
                </select>
                <canvas id="traffic"></canvas>
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
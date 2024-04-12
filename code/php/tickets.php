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
    <title>Tickets</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/tickets.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/searchuser.js"></script>
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
        <h1>Tickets</h1>
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
        <div id="tickets">
            <div id="inquiries">
                <form action="tickets.php" method="post" id="searchuser">
                    <input id="search" name="searchText" type="text" placeholder="Look up by email or date (YYYY-MM-DD)">
                </form>
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
                            if (isset($_POST["searchText"]) && !empty($_POST["searchText"])) {
                                $search = $_POST["searchText"];
                                $like = "%".$_POST["searchText"]."%";
                                $sql = "SELECT email, type, date, status FROM inquiry WHERE email = ? OR date LIKE ?";

                                if ($statement = mysqli_prepare($connection, $sql)) {
                                    mysqli_stmt_bind_param($statement, "ss", $search, $like);
                                    mysqli_stmt_execute($statement);
                                    mysqli_stmt_store_result($statement);

                                    if (mysqli_stmt_num_rows($statement) < 1) {
                                        echo "<p>There is no inquiry with email \"".$search."\" or date \"".$search."\"<p>";
                                    } else {
                                        echo "<table id='inquiry-list'>";
                                        echo "<tr class='center-header'><th>Email</th><th>Datetime</th><th>Type</th><th>Status</th></tr>";
                                    
                                        // fetch and display the result
                                        mysqli_stmt_bind_result($statement, $email, $type, $datetime, $status);

                                        while (mysqli_stmt_fetch($statement)) {
                                            echo "<tr>";
                                            echo "<td class='col-30'>" . $email . "</td>";
                                            echo "<td lass='col-30'>" . $datetime . "</td>";
                                            echo "<td class='col-20'>" . $type . "</td>";
                                            echo "<td class='col-20'>" . $status . "</td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                       
                                    }
                                    
                                } else {
                                    echo "Failed to prepare statement";
                                }

                                // close the statement and connection
                                mysqli_stmt_close($statement);
                                mysqli_close($connection);

                            } else {
                                $sql = "SELECT email, type, date, status FROM inquiry";
                                if ($statement = mysqli_prepare($connection, $sql)) {
                                    mysqli_stmt_execute($statement);
                                    mysqli_stmt_store_result($statement);

                                    if (mysqli_stmt_num_rows($statement) < 1) {
                                        echo "<p>No inquiry at this time.<p>";
                                    } else {
                                        echo "<table id='inquiry-list'>";
                                        echo "<tr class='center-header'><th>Email</th><th>Datetime</th><th>Type</th><th>Status</th></tr>";
                                    
                                        // fetch and display the result
                                        mysqli_stmt_bind_result($statement, $email, $type, $datetime, $status);

                                        while (mysqli_stmt_fetch($statement)) {
                                            if ($status === "completed") {
                                                $class = $status;
                                                $status = "Completed";
                                            } else {
                                                $class = "pending";
                                                $status = "Pending";
                                            }
                                            echo "<tr>";
                                            echo "<td class='col-30'>" . $email . "</td>";
                                            echo "<td lass='col-30'>" . $datetime . "</td>";
                                            echo "<td class='col-20'>" . $type . "</td>";
                                            echo "<td class='col-20'><span class=$class>" . $status . "</span></td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                        
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
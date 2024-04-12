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
    <title>Users</title>
    <link rel="stylesheet" href="../css/reset.css" />
    <link rel="stylesheet" href="../css/users.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/searchuser.js"></script>
    <script>
        function displayConfirmation() {
            if (confirm("Are you sure you want to delete this user?")) {
                return true; // Submit the form
            } else {
                return false; // Cancel submission
            }
        }
    </script>
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
        <h1>Users</h1>
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
        <div id="non-adminuser">
            <div id="users">
                <form action="users.php" method="post" id="searchuser">
                    <input id="search" name="searchText" type="text" placeholder="Look up by username or email">
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
                        $nonadmin = 0;
                        if (isset($_POST["searchText"]) && !empty($_POST["searchText"])) {
                            $search = $_POST["searchText"];
                            $sql = "SELECT uid, uname, email, usertype, i.imgid, file FROM user u LEFT JOIN image i ON u.imgid = i.imgid WHERE usertype = ? AND (uname = ? OR email = ?)";

                            if ($statement = mysqli_prepare($connection, $sql)) {
                                mysqli_stmt_bind_param($statement, "iss", $nonadmin, $search, $search);
                                mysqli_stmt_execute($statement);
                                mysqli_stmt_store_result($statement);

                                if (mysqli_stmt_num_rows($statement) < 1) {
                                    echo "<p>There is no user with username \"" . $search . "\" or email \"" . $search . "\"<p>";
                                } else {
                                    echo "<table id='userlist'>";
                                    echo "<tr class='center-header'><th>Profile Image</th><th>Username</th><th>Email</th><th>Action</th></tr>";

                                    // fetch and display the result
                                    mysqli_stmt_bind_result($statement, $userid, $uname, $email, $usertype, $imgid, $file);

                                    while (mysqli_stmt_fetch($statement)) {
                                        echo "<tr>";
                                        echo "<td class='col-20'><img src='data:image/jpeg;base64," . base64_encode($file) . "'></td>";
                                        echo "<td class='col-20'>" . $uname . "</td>";
                                        echo "<td class='col-48'>" . $email . "</td>";
                                        echo "<td class='col-12'><form action='deleteuser.php' method='post'><input type='hidden' name='uid' value='$userid'><input type='hidden' name='imgid' value='$imgid'><input id='delete' type='submit' value='Delete'></form></td>";
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
                            $sql = "SELECT uid, uname, email, usertype, i.imgid, file FROM user u LEFT JOIN image i ON u.imgid = i.imgid WHERE usertype = ?";
                            if ($statement = mysqli_prepare($connection, $sql)) {
                                mysqli_stmt_bind_param($statement, "i", $nonadmin);
                                mysqli_stmt_execute($statement);
                                mysqli_stmt_store_result($statement);

                                if (mysqli_stmt_num_rows($statement) < 1) {
                                    echo "<p>No user at this time.<p>";
                                } else {
                                    echo "<table id='userlist'>";
                                    echo "<tr class='center-header'><th>Profile Image</th><th>Username</th><th>Email</th><th>Action</th></tr>";

                                    // fetch and display the result
                                    mysqli_stmt_bind_result($statement, $userid, $uname, $email, $usertype, $imgid, $file);

                                    while (mysqli_stmt_fetch($statement)) {
                                        echo "<tr>";
                                        echo "<td class='col-20'><img src='data:image/jpeg;base64," . base64_encode($file) . "'></td>";
                                        echo "<td lass='col-20'>" . $uname . "</td>";
                                        echo "<td class='col-48'>" . $email . "</td>";
                                        echo "<td class='col-12'><form action='deleteuser.php' method='post' onsubmit='return displayConfirmation()'><input type='hidden' name='uid' value='$userid'><input type='hidden' name='imgid' value='$imgid'><input id='delete' type='submit' value='Delete'></form></td>";
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
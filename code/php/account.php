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
    <link rel="stylesheet" href="../css/account.css" />
    <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/validatepassword.js"></script>
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
            <a href="#">Account Profile</a>
            <a href="pricewatch.php">Your Price Watches</a>
            <?php
                if ($usertype === 1) {
                    echo "<a href='dashboard.php'>Dashboard</a>";
                    echo "<a href='#'>Products</a>";
                    echo "<a href='users.php'>Users</a>";
                    echo "<a href='tickets.php'>Tickets</a>";
                }
            ?>
            <a href="logout.php" id="logout">Sign out</a>
        </div>
        <div id="account-profile">
            <h2>Account Profile</h2>
            <div id="account-info">
                <form method="post" action="processaccount.php" id="setting-form">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value=<?php echo $uname; ?> placeholder=<?php echo $uname; ?>>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value=<?php echo $email; ?> placeholder=<?php echo $email; ?>>
                    <div id="password">
                        <label for="curr-pass">Current Password</label>
                        <input type="password" id="curr-pass" name="curr-pass" class="required">
                        <div id="new-password">
                            <div id="new">
                                <label for="new-pass">New Password</label>
                                <input type="password" id="new-pass" name="new-pass" class="required">
                            </div>
                            <div id="confirm">
                                <label for="con-pass">Confirm Password</label>
                                <input type="password" id="con-pass" name="con-pass" class="required">
                        </div>
                        </div>
                    </div>
                    <p class="status" style="color:#38AB38">
                        <?php echo $status;
                        $_SESSION["status"] = null; ?>
                    </p>
                    <input type="submit" id="save" name="save" value="Change password">
                </form>
            </div>
            <div id="profile-pic">
                <p>Profile Picture</p>
                <div id="picture">
                    <?php echo '<img id="pic" src="data:image/jpeg;base64,' . base64_encode($image) . '"/>'; ?>
                </div>
                <form id="pic-form" enctype="multipart/form-data" method="post" action="processchangeimg.php">
                    <input type="file" id="change-pic" name="change-pic">
                    <input type="submit" id="ch-pic" value="Change picture">
                </form>
                <p class="error" style="color:red">
                    <?php echo $chpic;
                    $_SESSION["chpic"] = null; ?>
                </p> 
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
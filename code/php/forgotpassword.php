<!DOCTYPE html>
<?php

session_start();

// redirect to home.php if the user already logged in
if (isset($_SESSION["uid"])) {
    header("Location: main.php");
    exit();
}

if (isset($_SESSION["status"])) {
    $status = $_SESSION["status"];
}

if (isset($_SESSION["error"])) {
    $error1 = $_SESSION["error"];
}

?>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/forgotpassword.css"/>
        <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="../js/validatelogin.js"></script>
    </head>
    <body>
        <header>
            <?php
                include_once("header.php");
            ?>
        </header>

        <main>
            <div id="wrap">
                <h1 class="welcome">RECOVER HERE</h1>
                <p class="welcome">Please provide your email address to reset your password.</p>
                <div id="forgot-info">
                    <form method="post" action="processforgot.php" id="signin-form">
                        <?php
                            if (isset($_SESSION["status"])) {
                                $status = $_SESSION["status"];
                                echo "<p class='status' style='color:#38AB38'>";
                                echo $status;
                                $_SESSION["status"] = null;
                                echo "</p>";
                            } elseif (isset($_SESSION["error"])) {
                                $error = $_SESSION["error"];
                                echo "<p class='error' style='color:red'>";
                                echo $error;
                                $_SESSION["error"] = null;
                                echo "</p>";
                            }
                        ?>
                        <div class="input">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="required" /> 
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <input type="submit" id="submit" value="Send email"/>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <footer>
            <?php
                include_once("footer.php");
            ?>
        </footer>
    </body>
</html>
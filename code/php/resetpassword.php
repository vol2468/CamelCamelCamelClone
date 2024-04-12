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
    $error = $_SESSION["error"];
}

?>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/resetpassword.css"/>
        <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="../js/validatepassword.js"></script>
    </head>
    <body>
        <header>
            <?php
                include_once("header.php");
            ?>
        </header>

        <main>
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET") { 
                    if (isset($_GET["uid"]) && !empty($_GET["uid"]))
                        $uid = $_GET["uid"];
                }
            ?>
            <div id="wrap">
                <h1 class="welcome">RESET PASSWORD</h1>
                <p class="welcome">Please enter your new password.</p>
                <div id="error-msg">
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
                </div>
                <div id="reset-info">
                    <form method="post" action="processchangepass.php" id="setting-form">
                        
                        <br>
                        <div class="input">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="required" /> 
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <label for="password">New Password</label>
                            <input type="password" id="new-pass" name="new-pass" placeholder="Enter your new password" class="required" /> 
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <label for="password">Confirm Password</label>
                            <input type="password" id="con-pass" name="con-pass" placeholder="Enter your new password" class="required" /> 
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <input type='hidden' name="uid" value=<?php echo $uid; ?>>
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <input type="submit" id="submit" value="Reset password"/>
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
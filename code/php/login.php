<!DOCTYPE html>

<?php
session_start();

if (isset($_SESSION["uid"])) {
    header("Location: main.php");
    exit();
}

if (isset($_SESSION["error"])) {
    $error = $_SESSION["error"];
}

if (isset($_SESSION["status"])) {
    $status = $_SESSION["status"];
}

?>

<html>
    <head lang="en">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Login</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/login.css"/>
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
                <h1 class="welcome">WELCOME BACK!</h1>
                <p class="welcome">Please enter your login details.</p>
                <div id="signin-info">
                    <form method="post" action="processlogin.php" id="signin-form">
                        <p class="error" style="color:red"><?php echo $error; $_SESSION["error"] = null; ?></p><br>
                        <p class="status" style="color:#38AB38"><?php echo $status; $_SESSION["status"] = null; ?></p><br>
                        <div class="input">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="required" /> 
                        </div>
                        <div class="input">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter your password" class="required" /> 
                        </div>
                        <p id="forgot"><a href="forgotpassword.php">Forgot Password?</a></p>
                        <div class="input">
                            <input type="submit" id="submit" value="Sign in"/>
                        </div>
                        <p>Don't have an account? <span><a id="sig" href="register.php">Sign up for free!</a></span></p>
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
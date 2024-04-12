<!DOCTYPE html>

<?php
session_start();

// redirect to home.php if the user already logged in
if (isset($_SESSION["uid"])) {
    header("Location: main.php");
    exit();
}

if (isset($_SESSION["exist"])) {
    $exist = $_SESSION["exist"];
}

?>
<html>
    <head lang="en">
        <meta charset="utf-8">
        <title>Register</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/register.css"/>
        <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=DM Sans' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="../js/validateregister.js"></script>

    </head>
    <body>
        <header>
            <?php
                include_once("header.php");
            ?>
        </header>

        <main>
            <div id="wrap">
                <h1 class="welcome">WELCOME!</h1>
                <p class="welcome">First create your account with a profile picture.</p>
                <div id="error-msg">
                    <p class="error" style="color:red"><?php echo $exist; $_SESSION["exist"] = null; ?></p>
                </div>
                <div id="regis-info">
                    <form enctype="multipart/form-data" method="post" action="processregister.php" id="register-form">
                        <div class="input" id="image">
                            <input type="file" id="profile-pic" name="profile-pic" class="required" />
                        </div>
                        <div class="input">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter your username" class="required" />
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" class="required" />
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <label for="password">Password</label>
                            <input type="password" name="password" placeholder="Enter your password" class="required" id="password" />
                            <p class="error-message"></p>
                        </div>
                        <div class="input">
                            <label for="password">Confirm Password</label>
                            <input type="password" name="confirm-password" placeholder="Re-enter your password" class="required" id="confirm-password"/>
                            <p class="error-message"></p>
                        </div>
                        <div id="message"></div> 
                        <br>
                        <div class="input">
                            <input type="submit" id="submit" value="Sign up"/>
                        </div>
                        <p>Already have an account? <span><a id="log" href="login.php">Login!</a></span></p>
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
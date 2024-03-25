<!DOCTYPE html>
<html>

<?php

session_start();

if (isset($_SESSION["uid"])) {
    // clear the session
    session_unset();
    session_destroy();

    // set the referring page
    $referer = $_SERVER["HTTP_REFERER"] ?? "login.php";
    header("Location: $referer");
    exit();
} else {
    header("Location: login.php");
    exit();
}

?>
</html>
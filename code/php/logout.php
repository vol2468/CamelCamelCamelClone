<!DOCTYPE html>
<html>

<?php

session_start();

if (isset($_SESSION["uid"])) {
    // clear the session
    session_unset();
    session_destroy();

    header("Location: main.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}

?>
</html>
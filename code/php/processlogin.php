<!DOCTYPE html>
<html>

<?php

session_start();

// redirect to home.php if the user already logged in
if (isset($_SESSION["uid"])) {
    header("Location: main.php");
    exit();
}

// using try catch statement to handle any error
try {
    // validate and obtain data passed through POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $email = $_POST["email"];
            $passwd = $_POST["password"];

            // hasing password
            $hashedPswd = md5($passwd);

            // database connection
            include "connect.php";

            if($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } else {
                // check if the email and password are valid in the database using a prepared statement
                $sql = "SELECT * FROM user WHERE email = ? AND password = ?";
                if ($statement = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($statement, "ss", $email, $hashedPswd);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);

                    if (mysqli_stmt_num_rows($statement) > 0) {
                        mysqli_stmt_bind_result($statement, $uid, $uname, $email, $passwd, $imgid, $usertype);
                        // create new session superglobal for username and redirect to main.
                        if (mysqli_stmt_fetch($statement)) {
                            $_SESSION["uid"] = $uid;
                            $_SESSION["usertype"] = $usertype;
                            header("Location: main.php");
                        }
                    } else {    // invalid credential
                        $_SESSION["error"] = "Email/Password is wrong";
                        header("Location: login.php?error=invalid1");
                        exit();
                    }
                } else {
                    header("Location: login.php?error=invalid2");
                    exit();
                }

                // close the statement and connection
                mysqli_stmt_close($statement);
                mysqli_close($connection);

            }
        
        } else {
            header("Location: login.php?error=incomplete");
            exit();
        }
    
    } else {
        header("Location: login.php?error=badrequest");
        exit();
    }

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
}


?>
</html>

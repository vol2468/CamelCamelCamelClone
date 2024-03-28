<!DOCTYPE html>
<html>

<?php

session_start();

if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
} else {
    $uid = $_SESSION["uid"];
    $usertype = $_SESSION["usertype"];
}

// using try catch statement to handle any error
try {
    // validate and obtain data passed through POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        if (isset($_POST["curr-pass"]) && !empty($_POST["curr-pass"]) && isset($_POST["new-pass"]) && !empty($_POST["new-pass"])) {
            $passwd = $_POST["curr-pass"];
            $newpassword = $_POST["new-pass"];

            // database connection
            include "connect.php";

            if($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } else {
                // check if the uid and password are valid using prepared statement
                $sql = "SELECT * FROM user WHERE uid = ? AND password = ?";
                if ($statement = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($statement, "is", $uid, $passwd);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);

                    if (mysqli_stmt_num_rows($statement) < 1) {
                        echo "<p>Current password is wrong<p>";
                    } else {
                        // update the user's password using a prepared statement
                        $sql = "UPDATE user SET password = ? WHERE uid = ?";
                    
                        if ($statement = mysqli_prepare($connection, $sql)) {
                            mysqli_stmt_bind_param($statement, "si", $newpassword, $uid);
                            mysqli_stmt_execute($statement);

                            if (mysqli_stmt_affected_rows($statement) > 0) {
                                // echo "User’s password has been updated.";
                                $_SESSION["status"] = "User’s password has been updated";
                                header("Location: account.php");
                                exit();
                            } else
                                echo "Failed to change";
                        }
                    }
                } else {
                    echo "Failed to prepare statement";
                }

                // close the statement and connection
                mysqli_stmt_close($statement);
                mysqli_close($connection);
            }



        } else {
            echo "<p>Empty fields exist. Please try again.<p>";
        }
    } else {
        echo "<p>The request method should be POST. Cannnot process the data.<p>";
    }

    

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

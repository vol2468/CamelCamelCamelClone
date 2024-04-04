<!DOCTYPE html>
<html>

<?php

session_start();

// using try catch statement to handle any error
try {
    // validate and obtain data passed through POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        if (isset($_POST["new-pass"]) && !empty($_POST["new-pass"]) && isset($_POST["uid"]) && !empty($_POST["uid"])) {
            $newpassword = $_POST["new-pass"];
            $uid = $_POST["uid"];

            // database connection
            include "connect.php";

            if($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
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

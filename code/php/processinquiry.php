<!DOCTYPE html>
<html>

<?php

session_start();

// using try catch statement to handle any error
try {

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["email"]) || empty($_POST["email"])) {
            $_SESSION["error"] = "Please provide you email.";
            header("Location: contactus.php");
            exit();
        } else {
            if (isset($_POST["subject"]) && !empty($_POST["subject"]) && isset($_POST["message"]) && !empty($_POST["message"])) {	
                $email = $_POST["email"];
                $type = $_POST["subject"];
                date_default_timezone_set('America/Vancouver');
                $datetime = date('Y-m-d H:i:s');
                $message = $_POST["message"];
                $status = "pending";
    
                // database connection
                include "connect.php";
                
                if($error != null) {
                    $output = "<p>Unable to connect to database!</p>";
                    exit($output);
                } else {
    
                    // store the tickets in the inquiry tables
                    $sql = "INSERT INTO inquiry VALUES (?, ?, ?, ?, ?)";
                    if ($statement = mysqli_prepare($connection, $sql)) {
                        mysqli_stmt_bind_param($statement, "sssss", $email, $type, $datetime, $message, $status);
                        mysqli_stmt_execute($statement);
    
                        echo mysqli_stmt_affected_rows($statement);
                        if (mysqli_stmt_affected_rows($statement) > 0) {
                            $_SESSION["status"] = "Your request is added to the queue.";
                            header("Location: contactus.php");
                            exit();
                        } else {
                            $_SESSION["error"] = "Something went wrong. Please try again.";
                            header("Location: contactus.php");
                            exit();
                        }
                    } else {
                        $_SESSION["error"] = "Failed to prepare statement.";
                        header("Location: contactus.php");
                        exit();
                    }
    
                    // close the statement and connection
                    mysqli_stmt_close($statement);
                    mysqli_close($connection);
                }
    
            } else {
                $_SESSION["error"] = "Empty fields exist. Please try again.";
                header("Location: contactus.php");
                exit();
            }
        }
	} else {
		$_SESSION["error"] = "The request method should be POST. Cannnot process the data.";
		header("Location: contactus.php");
		exit();
	}

} catch (Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

<!DOCTYPE html>
<html>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
            $email = $_POST["email-address"];

            // check the password combination
			$pattern = "/^(?=.*[@!?])(?=.*\d).{8,}$/";

			if (!preg_match($pattern, $newpassword)) {
				// password does not meet the criteria
				$_SESSION["error"] = "Password must be at least 8 characters long and contain at least one special character (@, !, or ?) and one digit.";
				header("Location: account.php");
				exit();
			}

            // hashing passwords
            $hashedPswd = md5($passwd);
            $hashedNewPswd = md5($newpassword);

            // database connection
            include "connect.php";

            if($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } else {
                // check if the uid and password are valid using prepared statement
                $sql = "SELECT * FROM user WHERE uid = ? AND password = ?";
                if ($statement = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($statement, "is", $uid, $hashedPswd);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);

                    if (mysqli_stmt_num_rows($statement) < 1) {
                        $_SESSION["error"] = "Current password is wrong";
                        header("Location: account.php");
                        exit();
                    } else {
                        // update the user's password using a prepared statement
                        $sql = "UPDATE user SET password = ? WHERE uid = ?";
                    
                        if ($statement = mysqli_prepare($connection, $sql)) {
                            mysqli_stmt_bind_param($statement, "si", $hashedNewPswd, $uid);
                            mysqli_stmt_execute($statement);
                            
                            if (mysqli_stmt_affected_rows($statement) > 0) {
                                // send email notifying password change
                                $to = $email;
                                $subject = "The Password Change of Your ATY Account";
                                $message = "Your password for your ATY account has been changed.";

                                require "phpmailer/src/Exception.php";
                                require "phpmailer/src/PHPMailer.php";
                                require "phpmailer/src/SMTP.php";

                                $mail = new PHPMailer(true);
                                $mail -> isSMTP();
                                $mail -> Host = "smtp.gmail.com";
                                $mail -> SMTPAuth = true;
                                $mail -> Username = "atycorp2024@gmail.com";
                                $mail -> Password = "vmlyrmweakdkkwpa";
                                $mail -> SMTPSecure = "ssl";
                                $mail -> Port = 465;

                                $mail -> setFrom("atycorp2024@gmail.com", "ATY Corp.");
                                $mail -> addAddress($to);
                                $mail -> isHTML(true);

                                $mail -> Subject = $subject;
                                $mail -> Body = $message;

                                $mail -> send();

                                $_SESSION["status"] = "User's password has been updated";
                                header("Location: account.php");
                                exit();
                            } else {
                                $_SESSION["error"] = "Password provided is the same / Failed to change the password";
                                header("Location: account.php");
                                exit();
                            }
                        }
                    }
                } else {
                    $_SESSION["error"] = "Failed to prepare statement.";
                    header("Location: account.php");
                    exit();
                }

                // close the statement and connection
                mysqli_stmt_close($statement);
                mysqli_close($connection);
            }



        } else {
            $_SESSION["error"] = "Empty fields exist. Please try again.";
            header("Location: account.php");
            exit();
        }
    } else {
        $_SESSION["error"] = "The request method should be POST. Cannnot process the data.";
        header("Location: account.php");
        exit();
    }

    

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

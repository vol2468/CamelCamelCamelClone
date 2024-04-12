<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        if (isset($_POST["email"]) && !empty($_POST["email"])) {
            $email = $_POST["email"];

            // database connection
            include "connect.php";

            if($error != null) {
                $output = "<p>Unable to connect to database!</p>";
                exit($output);
            } else {
                // check if the email and password are valid in the database using a prepared statement
                $sql = "SELECT uid FROM user WHERE email = ?";
                if ($statement = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($statement, "s", $email);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);

                    if (mysqli_stmt_num_rows($statement) > 0) {
                        mysqli_stmt_bind_result($statement, $uid);
                        if (mysqli_stmt_fetch($statement)) {

                            // modify the reset link when testing
                            $reset_link = "http://cosc360.ok.ubc.ca/yukiiso/COSC360Project/code/php/resetpassword.php?uid=".$uid;
                            // $reset_link = "http://localhost/COSC360Project/code/php/resetpassword.php?uid=$uid";
                            $to = $email;
                            $subject = "The Password Reset Link of Your ATY Account";
                            $message = "Click the link below to reset your password:\n\n$reset_link";

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

                            $_SESSION["status"] = "We have sent you a link to reset password. Please check!";
                            header("Location: login.php");
                            exit();
                        }
                    } else {    // invalid credential
                        $_SESSION["error"] = "We don't have an account accociated with the email.";
                        header("Location: forgotpassword.php?error=invalid1");
                        exit();
                    }
                } else {
                    header("Location: forgotpassword.php?error=invalid2");
                    exit();
                }

                // close the statement and connection
                mysqli_stmt_close($statement);
                mysqli_close($connection);

            }
        
        } else {
            header("Location: forgotpassword.php?error=incomplete");
            exit();
        }
    
    } else {
        header("Location: forgotpassword.php?error=badrequest");
        exit();
    }

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
}


?>
<?php

// using try catch statement to handle any error
try {
    // database connection
    include "connect.php";

    if ($error != null) {
        $output = "<p>Unable to connect to database!</p>";
        exit($output);
    } else {
        // Check if the user ID is provided via POST request
        if(isset($_POST["uid"])) {
            $userid = $_POST["uid"];
            $imgid = $_POST["imgid"];
            
            // Delete related records in the 'review' table first
            $delete_review_sql = "DELETE FROM review WHERE uid = ?";
            $delete_review_statement = mysqli_prepare($connection, $delete_review_sql);
            mysqli_stmt_bind_param($delete_review_statement, "i", $userid);

            if(mysqli_stmt_execute($delete_review_statement)) {
                // Delete user from the 'user' table
                $delete_user_sql = "DELETE FROM user WHERE uid = ?";
                $delete_user_statement = mysqli_prepare($connection, $delete_user_sql);
                mysqli_stmt_bind_param($delete_user_statement, "i", $userid);
                
                if(mysqli_stmt_execute($delete_user_statement)) {
                    // If user is deleted successfully, also delete their profile picture from the 'image' table
                    $delete_image_sql = "DELETE FROM image WHERE imgid = ?";
                    $delete_image_statement = mysqli_prepare($connection, $delete_image_sql);
                    mysqli_stmt_bind_param($delete_image_statement, "i", $imgid);
                    
                    if(mysqli_stmt_execute($delete_image_statement)) {
                        // echo "User and profile picture deleted successfully.";
                        header("Location: users.php");
                        exit();
                    } else {
                        echo "fail del pic";
                        // $_SESSION["error"] = "Failed to delete profile picture.";
                        header("Location: users.php?error=picture");
                        exit();
                    }
                } else {
                    echo "fail dele user.";
                    // $_SESSION["error"] = "Failed to delete user.";
                    header("Location: users.php?error=user");
                    exit();
                }

            } else {
                echo "Failed to delete related reviews.";
                // $_SESSION["error"] = "Failed to delete related reviews.";
                header("Location: users.php?error=reviews");
                exit();
            }
        } else {
            echo "Not post";
            header("Location: users.php?error=badrequest");
            exit();
        }
    }
} catch (Exception $e) {
    echo 'Error Message: ' . $e->getMessage();
}
?>

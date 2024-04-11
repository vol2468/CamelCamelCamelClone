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

function checkValidExtention($validExt, $validMime, $fileArray) {
	// get the extention of the filename e.g. user.jpg → jpg
	$extention = end(explode(".", $fileArray["name"]));
	$imageFileType = $fileArray["type"];
	return in_array($extention, $validExt) && in_array($imageFileType, $validMime);
}

function checkFileSize($maxFileSize, $fileArray) {
	return $fileArray["size"] < $maxFileSize;
}

// using try catch statement to handle any error
try {

	// validate and obtain data passed through POST request
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // database connection
        include "connect.php";
        
        if($error != null) {
            $output = "<p>Unable to connect to database!</p>";
            exit($output);
        } else {
            // handle file upload
            foreach ($_FILES as $fileKey => $fileArray) {
                if ($fileArray["error"] === UPLOAD_ERR_OK) {	// no error
                    // check the file type and size
                    $validExt = array("jpg", "png", "gif");
                    $validMime = array("image/jpeg", "image/png", "image/gif");
                    $imageFileType = $fileArray["type"];	// use later to store it into the database
                    $maxFileSize = 40000000;
                    
                    $validType = checkValidExtention($validExt, $validMime, $fileArray);
                    $validSize = checkFileSize($maxFileSize, $fileArray);

                    if ($validType && $validSize) {	// move file
                        $targetDir = "../uploads/user_img/";
                        $fileToMove = $fileArray["tmp_name"];
                        $destination = $targetDir.$fileArray["name"];

                        if (move_uploaded_file($fileToMove, $destination)) { // successfully moved
                            echo "<p>File successfully moved</p>";
                        
                        } else {
                            $_SESSION["chpic"] = "Failed moving file";
                            header("Location: account.php");
                            exit();
                        }

                    } else {
                        $_SESSION["chpic"] = "Invalid file type/size";
                        header("Location: account.php");
                        exit();
                    }

                } else { // error
                    echo "<p>Failed uploading file</p>";
                }
            }

            // retrive imgid from user
            $sql = "SELECT imgid FROM user WHERE uid = ?";
            if ($statement = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($statement, "i", $uid);
                mysqli_stmt_execute($statement);
                mysqli_stmt_store_result($statement);

                if (mysqli_stmt_num_rows($statement) < 1) {
                    echo "<p>Failed obtaining imgid<p>";
                } else {
                    mysqli_stmt_bind_result($statement, $imgid);
                    mysqli_stmt_fetch($statement);
                }
            } else {
                echo "Failed to prepare statement";
            }

            // insert image into the database
            $filePath = "../uploads/user_img/".$_FILES["change-pic"]["name"];
            $imagedata = file_get_contents($filePath);

            $sql = "UPDATE image SET file = ? WHERE imgid = ?";

            $stmt = mysqli_stmt_init($connection);
            mysqli_stmt_prepare($stmt, $sql);
            $null = NULL;
            mysqli_stmt_bind_param($stmt, "bi", $null, $imgid);
            mysqli_stmt_send_long_data($stmt, 0, $imagedata);

            $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));

            if ($result) {
                echo "Image changed successfully";
                header("Location: account.php");
                exit();
            } else {
                $_SESSION["chpic"] = "Failed to change the image";
                header("Location: account.php");
                exit();
            }
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
        }
	} else {
        $_SESSION["chpic"] = "The request method should be POST. Cannnot process the data.";
        header("Location: account.php");
        exit();
	}

} catch (Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

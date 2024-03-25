<!DOCTYPE html>
<html>

<?php
session_start();

if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
} else {
    $uid = $_SESSION["uid"];
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
            $filePath = "../uploads/user_img/".$_FILES["change-pic"]["name"];	// obtain the image from the uploads directory
            $imagedata = file_get_contents($filePath);
                            //store the contents of the files in memory in preparation for upload
            $sql = "UPDATE image SET file = ? WHERE imgid = ?";

            $stmt = mysqli_stmt_init($connection);		 //init prepared statement object
            mysqli_stmt_prepare($stmt, $sql);			 // register the query
            $null = NULL;
            mysqli_stmt_bind_param($stmt, "bi", $null, $imgid);
                            // bind the variable data into the prepared statement. You could replace $null with $data here and it also works. 
                            // you can review the details of this function on php.net. The second argument defines the type of
                            // data being bound followed by the variable list. In the case of the blob, you cannot bind it directly 
                            // so NULL is used as a placeholder. Notice that the parametner $imageFileType (which you created previously)
                            // is also stored in the table. This is important as the file type is needed when the file is retrieved from the database.
            mysqli_stmt_send_long_data($stmt, 0, $imagedata);
                            // This sends the binary data to the third variable location in the prepared statement (starting from 0).
            $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
                            // run the statement
            if ($result) {
                echo "Image changed successfully";
                header("Location: account.php");
                exit();
            } else {
                // echo "Failed to change image: ".mysqli_error($connection);
                $_SESSION["chpic"] = "Failed to change image";
                header("Location: account.php");
                exit();
            }
            mysqli_stmt_close($stmt); 					// and dispose of the statement.

            // close the statement and connection
            mysqli_close($connection);
        }
	} else {
		echo "<p>The request method should be POST. Cannnot process the data.<p>";
	}

} catch (Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

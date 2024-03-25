<!DOCTYPE html>
<html>

<?php

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
                    $targetDir = "../uploads/";
                    $fileToMove = $fileArray["tmp_name"];
                    $destination = $targetDir.$fileArray["name"];

                    if (move_uploaded_file($fileToMove, $destination)) { // successfully moved
                        echo "<p>File successfully moved</p>";
                    
                    
                    } else {
                        echo "<p>Failed moving file</p>";
                    }

                } else {
                    echo "<p>Invalid file type/size</p>";
                }

            } else { // error
                echo "<p>Failed uploading file</p>";
            }
        }

        // insert image into the database
        $filePath = "../uploads/".$_FILES["userImage"]["name"];	// obtain the image from the uploads directory
        $imagedata = file_get_contents($filePath);
                        //store the contents of the files in memory in preparation for upload
        $sql = "INSERT INTO image (file) VALUES (?)";

        $stmt = mysqli_stmt_init($connection);		 //init prepared statement object
        mysqli_stmt_prepare($stmt, $sql);			 // register the query
        $null = NULL;
        mysqli_stmt_bind_param($stmt, "b", $null);
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
            echo "Image inserted successfully";
            $imgid = mysqli_insert_id($connection);
        } else {
            echo "Failed to insert image: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt); 					// and dispose of the statement.


						
            mysqli_close($connection);
        
	} 

} catch (Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

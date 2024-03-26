<!DOCTYPE html>
<html>

<?php

// using try catch statement to handle any error
try {
    // database connection
    include 'connect.php';
    
			
    if($error != null) {
        $output = "<p>Unable to connect to database!</p>";
        exit($output);
    } else {
        
        // Insert user images
        for ($i=1; $i <= 10; $i++) { 
            $filePath = "../uploads/user_img/U".$i.".jpg";
            $imagedata = file_get_contents($filePath);

            $sql = "INSERT INTO image (fileType, file) VALUES ('jpg', ?)";
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
                echo "Image inserted successfully for U".$i.".jpg<br>";
                $imgid = mysqli_insert_id($connection);
            } else {
                echo "Failed to insert image: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt); 					// and dispose of the statement.
        }
        
        // Insert product images
        for ($i=1; $i <= 75; $i++) { 
            $filePath = "../uploads/product_img/P".$i.".jpg";
            $imagedata = file_get_contents($filePath);

            $sql = "INSERT INTO image (fileType, file) VALUES ('jpg', ?)";
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
                echo "Image inserted successfully for P".$i.".jpg<br>";
                $imgid = mysqli_insert_id($connection);
            } else {
                echo "Failed to insert image: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt); 					// and dispose of the statement.
        }


						
        mysqli_close($connection);
        
	} 

} catch (Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}
?>
</html>
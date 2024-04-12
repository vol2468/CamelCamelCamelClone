<!DOCTYPE html>
<html>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// redirect to main.php if the user already logged in
if (isset($_SESSION["uid"])) {
    header("Location: main.php");
    exit();
}

function checkValidExtention($validExt, $validMime, $fileArray) {
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
		// check the input field other than image
		if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["password"])) {	
            $uname = $_POST["username"];
			$email = $_POST["email"];
			$passwd = $_POST["password"];
            $usertype = 0;

			// check the password combination
			$pattern = "/^(?=.*[@!?])(?=.*\d).{8,}$/";

			if (!preg_match($pattern, $passwd)) {
				// Password does not meet the criteria
				$_SESSION["exist"] = "Password must be at least 8 characters long and contain at least one special character (@, !, or ?) and one digit.";
				header("Location: register.php");
				exit();
			}
			
			// hasing password
            $hashedPswd = md5($passwd);

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
								$_SESSION["exist"] = "Failed moving file. Please check your permission.";
								header("Location: register.php");
								exit();
							}

						} else {
							$_SESSION["exist"] = "Invalid file type/size.";
							header("Location: register.php");
							exit();
						}

					} else { // error
						echo "<p>Failed uploading file</p>";
					}
				}


				// check if the user already exists or using the same email using a prepared statement
				$sql = "SELECT * FROM user WHERE email = ?";
				if ($statement = mysqli_prepare($connection, $sql)) {
					mysqli_stmt_bind_param($statement, "s", $email);
					mysqli_stmt_execute($statement);
					mysqli_stmt_store_result($statement);

					if (mysqli_stmt_num_rows($statement) > 0) {
						$_SESSION["exist"] = "User already exists with this name and/or email.";
						header("Location: register.php");
						exit();
					} else {
                        // insert image into the database
						$filePath = "../uploads/user_img/".$_FILES["profile-pic"]["name"];
						$imagedata = file_get_contents($filePath);

						$sql = "INSERT INTO image (file) VALUES (?)";

						$stmt = mysqli_stmt_init($connection);
						mysqli_stmt_prepare($stmt, $sql);
						$null = NULL;
						mysqli_stmt_bind_param($stmt, "b", $null);
						mysqli_stmt_send_long_data($stmt, 0, $imagedata);

						$result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));

						if ($result) {
							echo "Image inserted successfully";
                            $imgid = mysqli_insert_id($connection);
						} else {
							echo "Failed to insert image: " . mysqli_error($connection);
						}
						mysqli_stmt_close($stmt);

						// insert the user using a prepared statement
						$sql = "INSERT INTO user (uname, email, password, imgid, usertype) VALUES (?, ?, ?, ?, ?)";
					
						if ($statement = mysqli_prepare($connection, $sql)) {
							mysqli_stmt_bind_param($statement, "sssii", $uname, $email, $hashedPswd, $imgid, $usertype);
							mysqli_stmt_execute($statement);

							if (mysqli_stmt_affected_rows($statement) > 0) {
								$uid = mysqli_insert_id($connection);
								echo "<p>An account for the user $uname has been created</p>";


								// send an email notification of account creation
								$to = $email;
                                $subject = "ATY Account Created";
                                $message = "Thank you for registering with an ATY account. Explore the best deals on Amazon's most affordable products.";

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


                                $_SESSION["uid"] = $uid;
								$$_SESSION["usertype"] = $usertype;
                                header("Location: main.php");
                                exit();
							} else {
								$_SESSION["exist"] = "Failed to create an account.";
                                header("Location: register.php");
                                exit();
							}
						}
					}
				} else {
					$_SESSION["exist"] = "Failed to prepare statement.";
					header("Location: register.php");
					exit();
				}

				// close the statement and connection
				mysqli_stmt_close($statement);
				mysqli_close($connection);
			}

		} else {
			$_SESSION["exist"] = "Empty fields exist. Please try again.";
			header("Location: register.php");
			exit();
		}
	} else {
		$_SESSION["exist"] = "The request method should be POST. Cannnot process the data.";
		header("Location: register.php");
		exit();
	}

} catch (Exception $e) {
	echo 'Error Message: ' .$e->getMessage();
}
?>
</html>

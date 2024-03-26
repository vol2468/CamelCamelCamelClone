<!DOCTYPE html>

<?php
session_start();

if (isset($_SESSION["uid"])) {
    $uid = $_SESSION["uid"];
}

?>

<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Header</title>
    <link rel="stylesheet" href="../css/reset.css"/>
    <link rel="stylesheet" href="../css/header.css"/>
    <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/header.js"></script>
</head>

<body>
<?php     
if (isset($_SESSION["uid"])) {
    // using try catch statement to handle any error
    try {        
        // database connection
        include "connect.php";
        
        if($error != null) {
            $output = "<p>Unable to connect to database!</p>";
            exit($output);
        } else {
            // check if the uid is valid using a prepared statement
            $sql = "SELECT * FROM user WHERE uid = ?";
            if ($statement = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($statement, "i", $uid);
                mysqli_stmt_execute($statement);
                mysqli_stmt_store_result($statement);
                
                if (mysqli_stmt_num_rows($statement) < 1) {
                    echo "<p>Invalid uid<p>";
                } else {
                    // fetch and display the result
                    mysqli_stmt_bind_result($statement, $uid, $uname, $email, $passwd, $imgid, $usertype);

                    mysqli_stmt_fetch($statement);
                    
                    // retrive image from the database
                    $sql = "SELECT file FROM image where imgid = ?";
                                    // build the prepared statement SELECTing on the userID for the user
                    $stmt = mysqli_stmt_init($connection);
                                    //init prepared statement object
                    mysqli_stmt_prepare($stmt, $sql);
                                    // bind the query to the statement
                    mysqli_stmt_bind_param($stmt, "i", $imgid);
                                    // bind in the variable data (ie userID)
                    $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
                                    // Run the query. run spot run!
                    mysqli_stmt_bind_result($stmt, $image); //bind in results
                                    // Binds the columns in the resultset to variables
                    mysqli_stmt_fetch($stmt);
                                    // Fetches the blob and places it in the variable $image for use as well
                                    // as the image type (which is stored in $type)
                    mysqli_stmt_close($stmt);
                                    // release the statement
                }

            } else {
                echo "Failed to prepare statement";
            }

            // close the statement and connection
            mysqli_stmt_close($statement);
            mysqli_close($connection);
        }
    
    } catch (Exception $e) {
        echo 'Error Message: ' .$e->getMessage();
    }
}            
?>
    <header>
        <div id="site-logo">
            <figure>
                <a href="main.php" target="_top"><img src="../images/logo_white.png" alt="logo" width="100px"/></a>
            </figure>
        </div>
        <div id="navigation-menu">
            <?php
                if (isset($_SESSION["uid"]))
                    echo "<a href='#'>Your Price Watches</a>";
            ?>
            <a href="popular.php">Popular Products</a> 
            <a href="topdrop.php">Top Price Drops</a>
            <form method="post" action="#">
                <select size="1" name="categoryName" id="category-dropdown">
                <input id="search" type="text" placeholder="      Search for products...">
                </select><input type="submit" value="Search" id="srcbtn">
            </form>
            <figure>
                <?php
                    if (!isset($_SESSION["uid"])) {
                        echo "<a href='login.php' target='_top'><img src='../images/account.png' alt='account' id='account-image'/></a>";
                        echo "<figcaption><a href='login.php'>sign in<a></figcaption>";
                    } else {
                        echo "<a href='account.php' target='_top'><img alt='account' id='account-image' src='data:image/jpeg;base64,".base64_encode($image)."'/></a>";
                        echo "<figcaption><a href='logout.php'>sign out<a></figcaption>";
                    }
                ?>
            </figure>
            <a href="#">Need help?</a>
        </div>
    </header>
</body>

</html>

<?php
    // database connection
    $host = "localhost";
    $database = "project";
    $user = "root";
    $password = "";
    
    $connection = mysqli_connect($host, $user, $password, $database);
    
    $error = mysqli_connect_error();
?>
<?php
    // $host = "localhost";
    // $database = "project";
    // $user = "root";
    // $password = "";

    $host = "cosc360.ok.ubc.ca";
    $database = "db_11888757";
    $user = "11888757";
    $password = "11888757";
    
    $connection = mysqli_connect($host, $user, $password, $database);
    
    $error = mysqli_connect_error();

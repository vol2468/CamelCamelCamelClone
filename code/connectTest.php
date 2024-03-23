<!DOCTYPE html>
<html lang="en">
<head>
</head>

<body>


<!-- Connecting to database here. -->
<?php
try{
    echo "hello1 <br>";   
    // $host = "cosc360.ok.ubc.ca";
    // $database = "db_11888757";
    // $user = "11888757";
    // $password = "11888757";
    // $connString = "mysql:host=cosc360.ok.ubc.ca;dbname=db_11888757";


    $host = "localhost";
    $database = "testdb";
    $user = "testuser";
    $password = "atytestpw";
    $connString = "mysql:host=localhost;dbname=testdb";

    $pdo = new PDO($connString, $user, $password);
    // $conn = mysqli_connect($host, $user, $password, $database);

    echo "hello2"."<br>";
    echo "<br>";
    
    $sql = "SELECT * FROM test";
    echo $sql."<br>";
    
    // $result = mysqli_query($conn, $sql);
    // echo "Result fetched <br> ";
    // echo $result;

    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        echo $row['eno']."-".$row['ename']."<br>";
    }

    echo "<br>";

    // method 1
    $sql = "SELECT * FROM test WHERE eno = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, "E0001");

    // method 2
    // $sql = "SELECT * FROM test WHERE eno = :eno";
    // $stmt = $pdo->prepare($sql);
    // $stmt->bindValue('eno', "E0001");

    echo $sql."<br>";
    // echo $stmt."<br>"; THIS DOES NOT WORK
    
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['eno']."-".$row['ename']."<br>";
    }


}catch(Exception $e){
    echo print($e);
    echo mysqli_connect_error();
}


?>

</body>
</html>


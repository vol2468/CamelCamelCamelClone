<?php
if (isset($_POST['selectedYear']) && !empty($_POST['selectedYear'])){
    $selectedYear = $_POST['selectedYear'];
} else {
    $output = "<p>No year selected</p>";
    exit($output);
}


try {
    // database connection
    include 'connect.php';

    if ($error != null) {
        $output = "<p>Unable to connect to database!</p>";
        exit($output);
    } else {

        $monthlyData = array_fill(0, 12, 0); // initialize record

        $sql = 'SELECT MONTH(date) as month, COUNT(date) FROM loginHistory WHERE YEAR(date) = ? GROUP BY month ORDER BY month';
        $statement = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($statement, "i", $selectedYear);
        mysqli_stmt_execute($statement);
        mysqli_stmt_store_result($statement);
        if (mysqli_stmt_num_rows($statement) > 0) {
            mysqli_stmt_bind_result($statement, $month, $count);
            for ($i=0; mysqli_stmt_fetch($statement); $i++) { 
                $monthlyData[$month-1] = $count;
            }
        } else {
            // No record in the specified year
            // No action required to be taken
        }

        echo json_encode($monthlyData);
    }

} catch (Exception $e) {
    echo 'Error Message: ' .$e->getMessage();
} finally {
    mysqli_close($connection);
}


?>
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
        <title>Camelcamelcamel Clone</title>
        <link rel="stylesheet" href="../css/reset.css"/>
        <link rel="stylesheet" href="../css/contactus.css"/>
        <link href='https://fonts.googleapis.com/css?family=Alata' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <header>
            <?php 
                include_once("header.php"); 
            ?>
        </header>

        <main>
            <div id="contact-info">
                <div id="contact-us">
                    <img src="../images/contactus.png">
                </div>
                <div id="query">
                    <form action="processinquiry.php" method="POST">
                        <label for="email"><h3>Email Address</h3></label><br>
                        <input type="email" id="email" name="email" class="required" />
                        <br>
                        <h2>Select Subject</h2>
                        <select name="subject" id="subject">
                            <option value="General Inquery">General Inquery</option>
                            <option value="Technical Issues">Technical Issues</option>
                            <option value="Bug Report">Bug Report</option>
                            <option value="Feature Report">Feature Report</option>
                        </select>
                        <br>
                        <label for="message"><h3>Message</h3></label><br>
                        <input type="text" id="message" name="message" placeholder="Write your message..." class="required" />
                        <br>
                        <div id="send-button">
                            <input type="submit" id="submit" value="Send message"/>
                        </div>
                    </form>
                    <div>
                        <?php 
                            if (isset($_SESSION["error"])) {
                                $error = $_SESSION["error"];
                                echo "<p class='error' style='color:red'>".$error."</p>";
                                $_SESSION["error"] = null;
                            } else if (isset($_SESSION["status"])) {
                                $status = $_SESSION["status"];
                                echo "<p class='status' style='color:#38AB38'>".$status."</p>";
                                $_SESSION["status"] = null;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <?php
                include_once("footer.php"); 
            ?>
        </footer>

    </body>
</html>
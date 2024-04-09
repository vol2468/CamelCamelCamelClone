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
                    <form action="" method="post">
                        <label for="email"><h3>Email Address</h3></label><br>
                        <input type="email" id="email" name="email" class="required" />
                        <br>
                        <h2>Select Subject</h2>
                        <select name="subject" id="subject">
                            <option value="general">General Inquery</option>
                            <option value="tech">Technical Issues</option>
                            <option value="bug">Bug Report</option>
                            <option value="feature">Feature Report</option>
                        </select>
                        <br>
                        <label for="message"><h3>Message</h3></label><br>
                        <input type="text" id="message" name="message" placeholder="Write your message..." class="required" />
                        <br>
                        <div id="send-button">
                            <input type="submit" id="submit" value="Send message"/>
                        </div>
                    </form>
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
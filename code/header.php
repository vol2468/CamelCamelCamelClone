<!-- CAUTION: After you update this file, 
    make sure to adjust the height value on header.js 
    so that all contents in this file is displayed correctly. 
-->

<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Header</title>
    <link rel="stylesheet" href="css/reset.css"/>
    <link href='https://fonts.googleapis.com/css?family=Actor' rel='stylesheet'>
    <link rel="stylesheet" href="css/header.css"/>
</head>

<body>
    <header>
        <div id="site-logo">
            <figure>
                <a href="main.html" target="_top"><img src="images/logo_white.png" alt="logo" width="100px"/></a>
            </figure>
        </div>
        <div id="navigation-menu">
            <a href="#">Popular Products</a> 
            <a href="#">Top Price Drops</a>
            <form class=form-fm method="get" action="listprod.jsp">
                <select size="1" name="categoryName">
                <option>All</option>
                <option>Sushi</option>
                <option>Seafood</option>
                <option>Set Menu</option>
                <option>Donburi</option>
                <option>Noodle</option>
                <option>Hot Pot</option>
                <option>Skewer</option>
                <input id="search" type="text" placeholder="      Search for products...">
                </select><input type="submit" value="Submit">
            </form>
            <figure>
                <a href="login.html" target="_top"><img src="images/account.png" alt="account" id="account-image"/></a>
                <figcaption><a href="login.html">sign in<a></figcaption>
            </figure>
            <a href="#">Need help?</a>
    <br>
</body>

</html>
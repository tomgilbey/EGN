<?php
require_once("functions.php");
session_start();
echo makePageStart("Staff Login", "styles.css", "gridContainer");
echo makeHeader("Events Guide North");
echo makeNavMenu("Categories", array("index.php" => "Home", "bookEventsForm.php" => "Book Event", "credits.php" => "Credits"));
?>

<form id="loginForm" action="loginProcess.php" method="post">
    <fieldset>
        <legend>Login</legend>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" >

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" >

        <input type="submit" value="Log in" >
    </fieldset>
</form> <!-- Form for login function -->

<?php
echo makeFooter("Events Guide North");
echo makePageEnd();
?>
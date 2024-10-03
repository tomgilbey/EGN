<?php
    require_once("functions.php");
    session_start();
    echo loggedIn();
    echo makePageStart("Update Event | Staff", "styles.css", "gridContainer");
    echo makeHeader("Events Guide North");
    echo makeNavMenu("Categories", array("index.php" => "Home", "bookEventsForm.php" => "Book Event", "credits.php" => "Credits"));

    list($input, $errors) = validateEventForm(); //Create an arry called list with given variables, and use validateEventForm() to ensure the details inputted are fine.
    if ($errors) { //If any errors are returned, do this.
        echo show_errors($errors); //Show any errors
    } else { //No errors, do this
        echo process_form($input); //Use process_form using the given inputs to update the event in DB.
    }       
        
    echo makeFooter("Events Guide North");
    echo makePageEnd();
    ?>

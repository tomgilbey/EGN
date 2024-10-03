<?php
require_once("functions.php");
session_start(); //Starts the session.
loggedIn(); //Ensures the user is loggedIn before loading the page.
echo makePageStart("Edit Event", "styles.css", "gridContainer");
echo makeHeader("Events Guide North");
echo makeNavMenu("Categories", array("index.php" => "Home", "bookEventsForm.php" => "Book Event", "credits.php" => "Credits"));

try{
$eventID = filter_has_var(INPUT_GET, 'eventID') ? $_GET['eventID'] : null; //This uses filter has var to check if the variable that has been sent via http is of specific input type. If its correct it assigns the passed value to $eventID and if not null value.

if (empty($eventID)) { 
    echo "<p>Please go back and choose an event to update.</p>\n"; //Will run if user doesn't pass through a value to edit.
} else {//If the eventID passed through http exists this will run.
    $dbConn = getConnection();

    $sqlEventQuery = "SELECT eventID, eventTitle, eventDescription, eventStartDate, eventEndDate, eventPrice, catID, venueID
                      FROM EGN_events
                      WHERE eventID = $eventID
                      ORDER BY eventTitle"; //Query to select all the values of the user's event

    $eventRecord = $dbConn->query($sqlEventQuery);//Runs query against db
    $rowObj = $eventRecord->fetchObject();//Fetches results as an object

    $getCatQuery = "SELECT catID, catDesc FROM EGN_categories";//Query to select all the category types
    $categoryTypes = $dbConn->query($getCatQuery);

    $getVenQuery = "SELECT venueID, venueName FROM EGN_venues";//Query to select all the venues
    $venueTypes = $dbConn->query($getVenQuery);

    echo "<form id='eventForm' action='updateEvent.php' method='GET'>\n
            <fieldset>\n
                <legend>Edit Event</legend>\n
                <input type='hidden' name='eventID' value='$eventID'>\n
            
                <label for='eventTitle'>Title:</label>\n
                <input type='text' name='eventTitle' id='eventTitle' value='{$rowObj->eventTitle}' required>\n

                <label for='eventDescription'>Description:</label>\n
                <textarea name='eventDescription' id='eventDescription' rows='4' cols='50' required>{$rowObj->eventDescription}</textarea>\n

                <label for='eventStartDate'>Start Date:</label>\n
                <input type='date' name='eventStartDate' id='eventStartDate' value='{$rowObj->eventStartDate}' required>\n

                <label for='eventEndDate'>End Date:</label>\n
                <input type='date' name='eventEndDate' id='eventEndDate' value='{$rowObj->eventEndDate}' required>\n

                <label for='eventPrice'>Price:</label>\n
                <input type='text' name='eventPrice' id='eventPrice' value='{$rowObj->eventPrice}' required>\n

                <label for='catID'>Category:</label>\n
                <select name='catID' id='catID' required>\n"; //Creates a form where all of the values for the user's selected event prepopulate the fields. Upon submission, updateEvent.php is run.
                echo "<option value='' disabled hidden>Select a category</option>\n";
    while ($category = $categoryTypes->fetchObject()) {//For each of the categorys fetched, do this.
        if ($category->catID == $rowObj->catID) {//If the categoryID of user passed through and from table are the same then do this
            echo "<option value='{$category->catID}' selected>{$category->catDesc}</option>\n";//Creates an option that is selected, meaning its the one that is shown on the select menu.
        } else {
            echo "<option value='{$category->catID}'>{$category->catDesc}</option>\n";//Creates general option for the user to change the category.
        }
    }
    echo "</select>\n";

    echo "<label for='venueID'>Venue:</label>\n
        <select name='venueID' id='venueID' required>\n
        <option value='' disabled hidden>Select a category</option>\n";
    while ($venue = $venueTypes->fetchObject()) {//Same as above
        if ($venue->venueID == $rowObj->venueID) {
            echo "<option value='{$venue->venueID}' selected>{$venue->venueName}</option>\n";
        } else {
            echo "<option value='{$venue->venueID}'>{$venue->venueName}</option>\n";
        }
    }
    echo "</select>\n";

    echo "<input type='submit' value='Update Event'>\n
        </fieldset>\n
        </form>\n";//Submit button to edit the event.
}
    }
    catch (Exception $e) {
        echo "Problem " . $e->getMessage();
    }
echo makeFooter("Events Guide North");
echo makePageEnd();
?>

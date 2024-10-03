<?php
    require_once("functions.php");
    session_start();
    echo loggedIn();
    echo makePageStart("Events List | Staff", "styles.css", "gridContainer");
    echo makeHeader("Events Guide North");
    echo makeNavMenu("Categories", array("index.php" => "Home", "bookEventsForm.php" => "Book Event", "credits.php" => "Credits"));
?>
    <?php
    try{
    $dbConn = getConnection(); //Gets connection to the DB

    $sqlQuery = "SELECT eventID, eventTitle, eventStartDate, eventEndDate, eventPrice, catDesc, venueName, location
                    FROM EGN_events
                    INNER JOIN EGN_categories
                    ON EGN_categories.catID = EGN_events.catID
                    INNER JOIN EGN_venues
                    ON EGN_venues.venueID = EGN_events.venueID
                    ORDER BY eventTitle"; //Query that selects all the events and orders them by title.
    $queryResult = $dbConn->query($sqlQuery); //Runs the query and stores it in variable.
    }
    catch (Exception $e) {
        echo "Problem " . $e->getMessage();
    }
?>

<table> <!-- Creates table will store the results of the query -->
    <thead>
        <tr>
            <th>Title</th> <!-- Each of these will be a heading for the table.-->
            <th>Category</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Venue</th>
            <th>Location</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php
            try{
            while ($rowObj = $queryResult->fetchObject()) { //While loop that loops through every query result.
                echo "<tr>
                        <td class='title'><a href='editEvent.php?eventID={$rowObj->eventID}'>{$rowObj->eventTitle}</a></td>\n
                        <td class='categoryName'>{$rowObj->catDesc}</td>\n
                        <td class='eventStartDate'>{$rowObj->eventStartDate}</td>\n
                        <td class='eventEndDate'>{$rowObj->eventEndDate}</td>\n
                        <td class='directorName'>{$rowObj->venueName}</td>\n
                        <td class='location'>{$rowObj->location}</td>\n
                        <td class='eventPrice'>{$rowObj->eventPrice}</td>\n
                      </tr>\n";
            }//Each of the values from the query are displayed under the headings.
        } 
        catch (Exception $e) {
            echo "Problem " . $e->getMessage();
        }
        ?>
    </tbody>
</table>
<?php
echo makeFooter("Events Guide North");
echo makePageEnd();
?>
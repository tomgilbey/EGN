<?php
    require_once("functions.php");
    session_start();
    echo makePageStart("Credits", "styles.css", "homeGridContainer");
    echo makeHeader("Events Guide North");
    echo makeNavMenu("Categories", array("index.php" => "Home", "bookEventsForm.php" => "Book Event", "credits.php" => "Credits"));
?>
<main>
    <p>Created by Tom Gilbey</p>
    <p>W22002938</p>
    <h2>References</h2>
    <p>Adams, V., Gajjar, K., Cyreal Oswold - Google Fonts., 2011., Google Fonts (https://fonts.google.com/specimen/Oswald) (Accessed:02/01/24)</p>
</main> <!-- References and name -->

<?php
echo makeFooter("Events Guide North");
echo makePageEnd();
?>
<!-- This code will be used on every visible page as it starts includes my functions file and then starts the session.-->
<?php
    require_once("functions.php");
    session_start();
    echo makePageStart("Home", "styles.css", "homeGridContainer"); #Home page so passing Home through echos it onto the title. The stylesheet is passed through, as is which container is getting used.
    echo makeHeader("Events Guide North"); #This is used to create the header at the top of the page.The parameter is the text that gets displayed.
    echo makeNavMenu("Categories", array("index.php" => "Home", "bookEventsForm.php" => "Book Event", "credits.php" => "Credits")); #This creates the navigation menu, and will be in every visible page. It includes the relevant links.
?>
<main>
<p>Welcome to Events Guide North (EGN), your premier destination for discovering and securing tickets to the most exciting and diverse events in the vibrant North East of England, with a special focus on the dynamic Newcastle area. Whether you're a local looking for the hottest concerts, cultural festivals, or sports events, or a visitor eager to experience the unique charm of the region, EGN is your one-stop hub for all things entertainment. Our user-friendly platform ensures a seamless ticket-buying experience, providing you with access to an array of handpicked events that cater to every taste and interest. From pulsating live music shows to engaging community gatherings, EGN is your passport to unforgettable experiences in the heart of the North East. Join us in celebrating the rich cultural tapestry of Newcastle and beyond – browse, book, and embark on a journey of entertainment with Events Guide North.</p>
<p>At EGN, we take pride in curating an extensive calendar that captures the essence of the Newcastle area's dynamic spirit. Our commitment goes beyond just ticket sales; we are dedicated to fostering a community of event enthusiasts. Explore our blog, where we share insider tips, interviews with local artists, and exclusive behind-the-scenes content. Connect with fellow attendees through our vibrant social media channels, and stay informed about the latest happenings through our newsletter. EGN is more than a ticketing platform – it's a gateway to the pulse of the North East's cultural scene. Join us in making memories and immersing yourself in the rich tapestry of experiences that define this extraordinary region. Your journey begins with Events Guide North – where every ticket is an invitation to an adventure.</p>
</main>
<aside id="offers"> <!-- This is the offers section, which displays a new offer every 5 seconds. -->
    <h2>Special Offers</h2>
    <div>
        <h3 id="eventTitle">Offer title</h3> <!-- ID used to change the value using innerHTML -->
        <p id="category">Event Category</p><!-- ID used to change the value using innerHTML -->
        <p id="price">Event Price</p><!-- ID used to change the value using innerHTML -->
        <form action="bookEventsForm.php">
        <button type="submit">Go to Book Events Form</button>
        </form><!-- This takes the user to the booking form if they see an event they like. -->
    </div>
</aside>
<script src="homeOffers.js"></script> <!-- The script home offers is called which gets each offer from the special offers table in DB.-->
<?php
    echo makeFooter("Events Guide North"); #Makes the footer of the page
    echo makePageEnd(); #Calls function to end the page.
?>
    

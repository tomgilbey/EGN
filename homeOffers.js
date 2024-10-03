window.addEventListener("load", function() {
    "use strict"; //The code will be ran when the page is fully loaded.

    const URL = 'getOffers.php'; //Sets constant to a link to getOffers.php

    function updateOffers(){ //Function that updates the offers on the home page.
    fetch(URL) //Initiates request to specified URL.
        .then( //Executed if fetch was succesful, converts response to JSON data.
            function (response) {
            return response.json();
            }
        )
        .then( //Code is executed if the response is successfully parsed as JSON
            function (json) {
                console.log(json);
                document.getElementById('eventTitle').innerHTML = json.eventTitle; //Changes the html text to the eventTitle that has been fetched. 
                document.getElementById('category').innerHTML = json.catDesc; //Changes text with ID category to the result
                document.getElementById('price').innerHTML = '£' + json.eventPrice; //Same as above

            }
        )
        .catch(
            function (error) { //if there is an error then
                console.log("Something went wrong!", error); //Display the error.
                document.getElementById('eventTitle').innerHTML = "No offers available"; //Tell user no offers are available
                document.getElementById('category').innerHTML = "";//Display nothing where ID is category
                document.getElementById('price').innerHTML = ""; //Same as above
            }
        );
        }

        updateOffers(); //Run the function straight away.

    setInterval(updateOffers, 5000); //Runs the function every 5 seconds.
});
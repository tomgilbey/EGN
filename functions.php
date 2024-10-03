<?php

ini_set('display_errors', 1); //allows system to display any errors
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);//reports all errors

function getConnection(){ //function to get the connection to the database, allows users to query
    try{
        $connection = new PDO("mysql:host=nuwebspace_db; dbname=w22002938","w22002938", "MH!UoL%Em0ph");//PDO is data abstraction layer
        $connection ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//sets attributes on PDO connection. Turns errors and exception reporting on.
        return $connection;
        
    }catch(Exception $e) {
        throw new Exception("Connection error".$e->getMessage(), 0 ,$e);
    }
}

function validateEventForm(){ //function that validates the user inputted data from the editEvent form
    $input = array(); //array called inputs
    $errors = array(); //array for all the errors

    $input['eventID'] = filter_has_var(INPUT_GET, 'eventID') ? $_GET['eventID'] : null; //Checks a value has been passed through and if not it becomes null.

    $input['eventTitle'] = filter_has_var(INPUT_GET, 'eventTitle') ? $_GET['eventTitle'] : null;//Checks title has been passed through, else set to null.
    $input['eventTitle'] = trim($input['eventTitle']); //trims any whitespace
    if (empty($input['eventTitle'])) {//ensures the inputted title isnt empty
        $errors[] = "Title name must not be empty";//adds this error to error list to report back to user.
    }
    if ((strlen($input['eventTitle']) > 256)) {//Checks if the title is too long
        $errors[] = "Title is too long.";
    }

    $input['eventDescription'] = filter_has_var(INPUT_GET, 'eventDescription') ? $_GET['eventDescription'] : null;//Same as above but for description
    $input['eventDescription'] = trim($input['eventDescription']);
    if (empty($input['eventDescription'])) {
        $errors[] = "Description name must not be empty";
    }
    if ((strlen($input['eventDescription']) > 256)) {
        $errors[] = "Description is too long.";
    }

    $input['eventStartDate'] = filter_has_var(INPUT_GET, 'eventStartDate') ? $_GET['eventStartDate'] : null;//Gets the user inputted start date
    if (empty($input['eventStartDate'])  || !preg_match("#^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$#", $input['eventStartDate'])) { //checks if the user inputted start date is valid in YYYY/MM/DD
        $errors[] = "Start date must not be empty and should be in format yyyy-mm-dd.";
    }
    else{
        list($y, $m, $d) = explode("-", $input['eventStartDate']);//Seperates each of the values of date into 3 variables
        if (empty($y)|| empty($m)|| empty($d)){//checks if any of the values are empty
            $errors[] = "You're missing an aspect of the Start date.";
        }
        if(!strlen($y)==4 || !strlen($m)==2 || !strlen($d)==2 )//Ensures that the length is not more than it can be
        {
            $errors[] = "You're date isn't in the correct format. 4 digits for year, and 2 for month and day.";
        }
        if (!is_numeric($y) || !is_numeric($d) || !is_numeric($m))//Ensures dates are a numeric value
        {
            $errors[] = "You're start date isn't all integers.";
        }
        else{
            if(!checkdate($m, $d, $y))//uses checkdate function to ensure the date is valid
            {
                $errors[] = "Start date is invalid.";
            }  
        } 
    }

    $input['eventEndDate'] = filter_has_var(INPUT_GET, 'eventEndDate') ? $_GET['eventEndDate'] : null;
    if (empty($input['eventEndDate']) || !preg_match("#^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$#", $input['eventEndDate']) ) {
        $errors[] = "End date must not be empty and should be in format yyyy-mm-dd.";//Same as above
    }
    else{
        list($y, $m, $d) = explode("-", $input['eventEndDate']);
        if (empty($y)|| empty($m)|| empty($d) ){
            $errors[] = "You're missing an aspect of the End date.";
        }
        if(!strlen($y)==4 || !strlen($m)==2 || !strlen($d)==2 )
        {
            $errors[] = "You're date isn't in the correct format. 4 digits for year, and 2 for month and day.";
        }
        if (!is_numeric($y) || !is_numeric($d) || !is_numeric($m))
        {
            $errors[] = "You're end date isn't all integers.";
        }
        else{
            if(!checkdate($m, $d, $y))
            {
                $errors[] = "End date is invalid.";
            }  
        } 
    }
    $input['eventPrice'] = filter_has_var(INPUT_GET, 'eventPrice') ? $_GET['eventPrice'] : null;//same as above
    $input['eventPrice'] = trim($input['eventPrice']);
    if (empty($input['eventPrice'])) {
        $errors[] = "Price must not be empty";
    }
    else{
        if ((strlen($input['eventPrice']) > 6)) {//checks price isnt too large
            $errors[] = "Price is too large.";
        }
        if(!is_numeric($input['eventPrice'])){
            $errors[] = "Price needs to be a number.";
        }
        else{
            if(strpos($input['eventPrice'], '.')!==false){//checks if eventPrice contains a .
                list($pounds, $pence) = explode(".", $input['eventPrice']);//SPlits into pounds and pence
                if (!(strlen($pence)==2)){
                    $errors[] = "Price has to have 2 pence digits.";
                }//ENsures there is always 2 digits after the . 
        }
    }
    }

  $input['catID'] = filter_has_var(INPUT_GET, 'catID') ? $_GET['catID'] : null;//same as above
$input['catID'] = trim($input['catID']);
if (empty($input['catID'])) {
    $errors[] = "Category must not be empty";
} else {
    try {
        $dbConn = getConnection();
        
        //Fetch all category IDs from the EGN_categories table
        $categoriesQuery = "SELECT catID FROM EGN_categories";
        $categoryIDsResult = $dbConn->query($categoriesQuery);

        if ($categoryIDsResult) {
            //Store all category IDs as array
            $categoryIDs = $categoryIDsResult->fetchAll(PDO::FETCH_COLUMN);

            //Check if inputted category ID is in the list of valid category IDs
            if (!in_array($input['catID'], $categoryIDs)) {//CHecks if user inputted carID is in the table
                $errors[] = "Category inputted isn't in the database, they should be in the format cx where x is an integer.";
            }
        } else {
            // Handle query execution error
            $errors[] = "Error executing the query: " . $dbConn->errorInfo()[2];
        }
    } catch (Exception $e) {
        // Handle other exceptions
        $errors[] = "Error: " . $e->getMessage();
    }
}



$input['venueID'] = filter_has_var(INPUT_GET, 'venueID') ? $_GET['venueID'] : null;//Same as category
$input['venueID'] = trim($input['venueID']);
if (empty($input['venueID'])) {
    $errors[] = "Venue must not be empty";
} else {
        try {
            $dbConn = getConnection();
            
            // Fetch all venueIDs from the EGN_venues table
            $venuesQuery = "SELECT venueID FROM EGN_venues";
            $venueIDsResult = $dbConn->query($venuesQuery);

            if ($venueIDsResult) {
                // Fetch all venueIDs as an array
                $venueIDs = $venueIDsResult->fetchAll(PDO::FETCH_COLUMN);

                // Check if the inputted venueID is in the list of valid venueIDs
                if (!in_array($input['venueID'], $venueIDs)) {
                    $errors[] = "Venue inputted isn't in the database, they should be valid venueIDs, starting with a v followed by integer.";
                }
            } else {
                // Handle query execution error
                $errors[] = "Error executing the query: " . $dbConn->errorInfo()[2];
            }
        } catch (Exception $e) {
            // Handle other exceptions
            $errors[] = "Error: " . $e->getMessage();
        }
    
    }



    return array($input, $errors);//returns both values.
    
}

function show_errors($errors) {//function to show errors, parameter should be array of errors or empty
    echo "<h1 class='error-heading'>Errors</h1>\n";
    $output = "";
    foreach ($errors as $error) {
        $output .= "<p class='error-message'>$error</p>\n";//Concatenates each error into an error message and displays on screen.
    }
    return $output;
}

function process_form($input){//Function that processes the form entry from editEvent, parameter is the inputs that the user edited.
    try{
        $dbConn= getConnection();
        $updateEventQuery = "UPDATE EGN_events 
                    SET eventTitle=:eventTitle,
                        eventDescription=:eventDescription, 
                        venueID=:venueID,
                        catID=:catID, 
                        eventStartDate=:eventStartDate, 
                        eventEndDate=:eventEndDate, 
                        eventPrice=:eventPrice
                    WHERE eventID = :eventID";//Query that updates the users changes in the DB table, if the eventID matches
        $stmt = $dbConn->prepare($updateEventQuery);//Prepares a statement for executing the query, protects against SQL injections and is highly efficient.
        $stmt ->execute(array(':eventTitle' =>$input['eventTitle'],
                            ':eventDescription' =>$input['eventDescription'],
                            ':venueID' =>$input['venueID'],
                            ':catID' =>$input['catID'],
                            ':eventStartDate' =>$input['eventStartDate'],
                            ':eventEndDate' =>$input['eventEndDate'],
                            ':eventPrice' =>$input['eventPrice'],
                            ':eventID' =>$input['eventID']));//Executes prepared statement with given parameters. Values from $input array are binded to placeholders. Execute is used to execute the statement.
    }catch(Exception $e){
        echo "<p>Query failed: ".$e->getMessage()."</p>\n";
    }
    return "<p>Successfully updated</p>\n";
}


function validate_login(){//Function to validate the login fields
    $input = array();
    $errors=array();
    $input['username'] = $_POST['username']?? '';//Gets the username user has inputted by POST
    $input['passsword'] = $_POST['password']?? '';//Same as above
    $input['username'] = trim($input['username']);//Trims the whitespace
    $input['passsword'] = trim($input['passsword']);

    try{
        require_once("functions.php");
        $dbConn = getConnection();

        $sqlQuery = "SELECT username, passwordHash FROM EGN_users WHERE username = :username";//Gets the valid username and passwordHash from DB
        
        $stmt = $dbConn->prepare($sqlQuery);//Prepare statement
        
        $stmt->execute(array(':username' => $input['username']));// Execute the statement, passing in the submitted username

        $user = $stmt->fetchObject();//Stores the result as object
        if($user){
            $passwordHash = $user->passwordHash;//Hashes the user inputted password
            if (password_verify($input['passsword'], $passwordHash))//Checks if the hashed version of user inputted password is same as the password hash that matches the username.
                {
                    $_SESSION['username'] = $user->username; //Set the session username to the user's username
                }
            
            else {
                $errors[] = "Password is incorrect";
                }//tells user password is wrong
            }
        else {
            $errors[] = "Username is incorrect.";//Tells user username doesnt exist
        }
    
    }catch (Exception $e) {
            echo "There was a problem: " . $e->getMessage();
    }
    return array($input, $errors);

}

function set_session($key, $value){//Function that takes two parameters to specify the session key and the corresponding value
    $_SESSION[$key]=$value;//Assigns provided value to the session variable thats identified by $key.
    return true;
}

function get_session($key){//Function designed to get the value of session variable based on key.
    if (isset($_SESSION[$key]))//Checks if session variable with the given key exists
    {
        return $_SESSION[$key];//If session exists, it will return the correct value.
    }
    return null;
}

function check_login(){//Checks if user is logged-in based on session variable "logged-in".
    if (get_session('logged-in')){//Checks if session variable with key "logged-in" exists and if it is true.
        return true;//Returns true if user is logged in
    }
    return false;//WIll return false if user isnt logged in

}

function loggedIn(){//Function that redirects users to loginform.php if they are not logged in.
    if (!check_login())
        {
            header('Location: loginForm.php');//Redirects user
            exit();//Terminates script
        }
}

function logOut(){//Function to log out of account
    $_SESSION = array();//Reset session array
    session_destroy();//Destroys current session
    header('Location: index.php');//Redirects user to home page.
    exit();//Terminates script
}

function makePageStart($title, $cssfile, $divName) {//Used to make the page start. $title changes <title>, $cssfile is used for stylesheet and div name is used to change the div name
    $pageStartContent = <<<PAGESTART
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>$title</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="$cssfile" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div id="$divName">
    PAGESTART;
    $pageStartContent .= "\n";
    return $pageStartContent;
    //Heredoc syntac to store block of text in php. Creates the start of the webpage
}

function makeHeader($headerText) {//Used to create the header of every page. Allows for reusability
    $headerContent = <<<HEADER
    <header>
        <h1>$headerText</h1>
    </header>
    HEADER;
    $headerContent .= "\n";
    return $headerContent;
}

function makeNavMenu($navMenuHeader, array $links) {//Creates Nav menu, where the header is passsed through, as is the links for this.
    $output= "<nav>\n";//Creates output that will be displayed.
    $output.= "<ul>\n";
    foreach ($links as $key => $value) {//For each link passed through do this
        $output .= "<li><a href=".$key.">$value</a></li>\n";//add the link to the list
        }
    if(check_login()===true){//if the user is logged in display below
    $output .= "<li><a href='chooseEvent.php'>Choose Event</a></li>\n";
    $output .= "<li><a href='logout.php'>LogOut</a></li>\n";
    }
    else{//User not logged in so display this.
        $output .= "<li><a href='loginForm.php'>Login</a></li>\n";
    }
    $output .="</ul>\n";  
    $output .="</nav>\n";    
    return $output;
    $navMenuContent = <<<NAVMENU
    <nav>
        <h2>$navMenuHeader</h2>
        
            $output;
            

    </nav>
    NAVMENU;
    $navMenuContent .= "\n";
    return $navMenuContent;
} 

function makeFooter($footerText) {//FUnction that creates the footer for the page where i can input text when calling it that is displayed in the footer
    $footerContent = <<<FOOTER
    <footer>
        <p>$footerText</p>
    </footer>
    FOOTER;
    $footerContent .= "\n";
    return $footerContent;
}

function makePageEnd() {//Makes the page end by closing hte div, body and html tags.
    return "</div>\n</body>\n</html>\n";
}



?>
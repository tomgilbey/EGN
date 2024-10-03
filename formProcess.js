window.addEventListener('load', function() {
    "use strict"; //Run when page is loaded

    function termsAccept(event) { //Function that checks if terms and conditions checkbox is selected.
        const checkbox = document.querySelector("input[name=termsChkbx]"); //Selects the checkbox element with name termsChkbx
        const submit = document.querySelector("input[name=submit]"); //Selects submit element with name submit
        if (checkbox.checked) {
            //Code executed if the checkbox is checked
            event.currentTarget.style.color = "black"; //Changes css
            event.currentTarget.style.fontWeight = "normal";
            submit.disabled = false; //Enables the submit button to be pressed
        }
        else{ //If its not selected do this
            event.currentTarget.style.color = "#FF0000"; //Make the text red
            event.currentTarget.style.fontWeight = "bold"; //Make text bold
            submit.disabled = true; //Disable the submit button
        }
    }

    const termsChecked = document.getElementById("termsText"); //Selects the HTML element with id termsText
    termsChecked.addEventListener("click", termsAccept); //Adds event listener for when this is clicked.




    const zeroPrice = 0.00; //Set variable to 0
    const bookingForm = document.getElementById("bookingForm"); //Get element with ID bookingForm
    bookingForm.total.value = zeroPrice.toFixed(2); //Set the total value to 0, with  decimal places, so 0.00
    if (bookingForm) { //If form with ID bookingForm exists.
        const checkboxes = bookingForm.querySelectorAll("input[name='event[]']"); //Select all checkboxes with name event[]
        const radiobox = bookingForm.querySelectorAll("input[name='deliveryType']"); //Se;ect all radio buttons wit name deliveryType
        checkboxes.forEach(function(checkbox) { //For each checkbox, add an event listener for a change
            checkbox.addEventListener("change", calculateTotal);
        });

        radiobox.forEach(function(radio) { //Same as above
            radio.addEventListener("change", calculateTotal);
        });
    }

    let oneChecked = false; //Initialise variable to false
    function calculateTotal(){ //Updates total based on checkbox and radiobuttons.
        oneChecked=false; //Set one checked to false
        let grandTotal = 0; //grandTotal is 0
        const events = bookingForm.querySelectorAll("div.item"); //Select all divs with class item
        for (const event of events) { //Find the checkbox within each item div, that has data-price attribute.
            const eventCheckbox = event.querySelector("input[data-price][type=checkbox]");
        
            if (eventCheckbox.checked) { //If the checkbox is checked do this.
                grandTotal += parseFloat(eventCheckbox.dataset.price); //Add the float value from the event price to the grandTotal
                oneChecked = true; //Set one checked to true
            }
        }

        
        console.log(grandTotal); //Display the grandTotal in system
        bookingForm.total.value = grandTotal.toFixed(2); //Set the total in the booking form to grandTotal to 2 dp
        
    

        const deliveryOptions = bookingForm.querySelectorAll("input[type=radio][data-price]"); //Select all radio buttons with data-price attribute
        for (const option of deliveryOptions) { 
            if (option.checked) {
                grandTotal += parseFloat(option.dataset.price); //If radio butto is checked, add the value to grandTotal
            }
        if (!oneChecked) { //If there isnt a checkbox checked then grandTotal is always 0, so it will always go back to 0
            grandTotal = 0;
        }
        bookingForm.total.value = grandTotal.toFixed(2); //Update the value on the form.
        return oneChecked;
    }
}

bookingForm.submit.addEventListener("click", checkForm); //Add event listener on clikc for checkForm

function checkForm(event) { //To check the form is correctly used.

    let validationFailed = false; //Set variable to false

    // Check if forename is empty
    if (bookingForm.forename.value.trim().length === 0) {
        validationFailed = true;
    }

    // Check if surname is empty
    if (bookingForm.surname.value.trim().length === 0) {
        validationFailed = true;
    }

    // Check if at least one checkbox is checked
    if (!oneChecked) {
        validationFailed = true;
    }


    if (validationFailed) { //If validationFailed is true, then notify the user
        alert("Validation has failed, please ensure you have chosen an event and inputted your Forename and Surname");
        event.preventDefault(); //Prevent default form submission behaviour.
    }
}

});

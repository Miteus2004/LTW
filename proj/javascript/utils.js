
const alertMessageBox = document.getElementById("alertMessageBox");

// Messages

function showErrorMessage(messageText){

    let alert = document.createElement("div");
    alert.classList.add("error", "alertMessage");
    let errorIcon = document.createElement("i");
    errorIcon.classList.add("fa-solid", "fa-circle-xmark");
    let message = document.createElement("p");
    message.textContent = messageText;

    alert.appendChild(errorIcon);
    alert.appendChild(message);
    addMessage(alert);

}

function showSuccessMessage(messageText){

    let alert = document.createElement("div");
    alert.classList.add("success", "alertMessage");
    let errorIcon = document.createElement("i");
    errorIcon.classList.add("fa-solid", "fa-circle-check");
    let message = document.createElement("p");
    message.textContent = messageText;

    alert.appendChild(errorIcon);
    alert.appendChild(message);
    addMessage(alert);
}


function showWarningMessage(messageText){


    let alert = document.createElement("div");
    alert.classList.add("warning", "alertMessage");
    let errorIcon = document.createElement("i");
    errorIcon.classList.add("fa-solid", "fa-circle-exclamation");
    let message = document.createElement("p");
    message.textContent = messageText;

    alert.appendChild(errorIcon);
    alert.appendChild(message);
    addMessage(alert);
}

function addMessage(messageItem){
    messageItem.classList.add("alertMessage");
    alertMessageBox.appendChild(messageItem);


    setTimeout(function (){
        messageItem.remove();
    }, 3000);
}


// Helper functions


function extractid(id){
    return id.split('-')[1];
}


function validatePriceInput(input) {
    // Regular expression to match a price with two decimal places
    let regex = /^\d+(,\d{1,2})?$/
    return regex.test(input);
}


function trimWhitespaceAndLineBreaks(text) {
    return text.replace(/^\s+|\s+$/g, '');
}


function formatDate(datevalue, seconds){
    if(datevalue !== ""){
        if(seconds){
            const [datePart, timePart] = datevalue.split(" ");

            // Split the date part by hyphen to get year, month, and day
            const [year, month, day] = datePart.split("-");

            // Split the time part by colon to get hour and minute
            const [hour, minute] = timePart.split(":");

            const formattedDate = day + "-" + month + "-" + year + " " + hour + ":" + minute;

            return formattedDate;

        }
        else{
            const date = datevalue.split("-");
            // Format the date in the desired format (e.g., "DD/MM/YYYY")
            const formattedDate = date[2] + "-" + date[1] + "-" + date[0];
            // Update the value of the text input with the formatted date
            return formattedDate;
        }

    }
    return "";

}
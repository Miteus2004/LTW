document.addEventListener("DOMContentLoaded",function(){

    const sendButton = document.getElementById('send-btn');
    
    sendButton.addEventListener('click', function(){

        sendButton.disabled = true;

        const product = document.querySelector('.product');
        const id = extractid(product.getAttribute('id'));
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../actions/action_sendproduct.php', true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) { 
                if (xhr.status === 200) { 
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log(response.data);
                        if(response['status'] == 'success'){
                            console.log('Send successful:', response);
                            window.location.href = '../pages/mainpage.php';
                        }   
                        else{
                            console.log("ERROR BUYING");
                        }
                    } catch (e) {
                        console.error('Error parsing response JSON:', e);
                        alert('There was an error processing your purchase. Please try again.');
                    }
                } else {
                    console.error('There was a problem with the request:', xhr.statusText);
                    alert('There was an error with your purchase. Please try again.');
                }
                // Re-enable the button to allow retry
                document.getElementById('send-btn').disabled = false;
            }
        
        };
        xhr.send(JSON.stringify(id));



    })


    document.getElementById('print-btn').addEventListener('click', function() {
        window.print();
    });

})
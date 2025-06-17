const formPreferences = document.getElementById('preferenceForm');

formPreferences.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the default form submission
    const preferences = document.getElementById('preferences').value; // Get the value of the input
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './resources/php/preferenceUpload.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`preferences=${encodeURIComponent(preferences)}`); // Send the preferences

    xhr.onload = function() {
        if (xhr.status === 200) { // Check for successful response
            const response = xhr.responseText;
            console.log(response); // Log the response for debugging
            if (response) { // Check if response is not empty
                alert(response); // Successful submission
            } else {
                console.error('No response received.'); // Handle empty response
            }
        } else {
            console.error('Request failed with status: ' + xhr.status); // Handle error
        }
    };

    xhr.onerror = function() {
        console.error('Request failed');
        alert('An error occurred while processing your request. Please try again.');
    };
});
const formMakeEvent = document.getElementById("makeEvent");
formMakeEvent.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formMakeEvent);
    $('#tags option').each(function() {
        formData.append('tags[]', $(this).val());
    });
    const xhr = new XMLHttpRequest();
    xhr.open('POST', formMakeEvent.action, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Request was successful
            console.log('Success:', xhr.responseText);
            // Optionally, you can redirect or update the UI here
        } else {
            // Handle errors here
            console.error('Error:', xhr.statusText);
        }
    };

    // Send the request with the form data
    xhr.send(formData);
});

const formMakeOrganization = document.getElementById("makeOrganization");
formMakeOrganization.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formMakeOrganization);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', formMakeOrganization.action, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.success); // Show success message
                // Optionally, you can clear the form or redirect
            } else if (response.errors) {
                alert("Errors: " + response.errors.join(", ")); // Show error messages
            } else {
                alert("Unexpected response.");
            }
        } else {
            console.error('Error:', xhr.statusText);
        }
    };

    // Send the request with the form data
    xhr.send(formData);
});
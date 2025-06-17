const formChangePassword = document.getElementById("changePasswordForm");
formChangePassword.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formChangePassword);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', formChangePassword.action, true);
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
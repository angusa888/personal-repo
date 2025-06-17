// Get modal elements
var modal1 = document.getElementById("loginModal");
var modal2 = document.getElementById("signupModal");
var openModal1 = document.getElementById("openModal1");
var openModal2 = document.getElementById("openModal2");
var closeBtn1 = document.getElementsByClassName("close")[0];
var closeBtn2 = document.getElementsByClassName("close")[1];

// Open modal when clicked the button
openModal1.addEventListener("click", function() {
    modal1.style.display = "block";
});

openModal2.addEventListener("click", function() {
    modal2.style.display = "block";
});

// Close modal when clicked the close button
closeBtn1.onclick = function() {
    modal1.style.display = "none";
};

closeBtn2.onclick = function() {
    modal2.style.display = "none";
};

// Close modal when clicking outside modal content
window.onclick = function(event) {
    if (event.target == modal1) {
        modal1.style.display = "none";
    } else if (event.target == modal2) {
        modal2.style.display = "none";
    }
};

const formLogin = document.getElementById('loginForm');

formLogin.addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('usernameLogin').value;
    const password = document.getElementById('passwordLogin').value;
    const csrfTokenLogin = document.getElementById('csrf-token-login').value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './resources/php/login.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('x-csrf-token', csrfTokenLogin);
    xhr.send(`usernameLogin=${encodeURIComponent(username)}&passwordLogin=${encodeURIComponent(password)}`); // Added '&' separator
    xhr.onload = function() {
        if (xhr.status === 200) { // Use strict equality
            const response = xhr.responseText;
            if (response === 'success') { // Use strict equality
                window.location.href = './home/home.php';
            } else {
                xhr.onerror = function() {
                    console.error('Request failed');
                    alert('An error occurred while processing your request. Please try again.');
                };
                document.getElementById("l-val-text").style.display = "flex";
                document.getElementById("l-val-msg").innerHTML = response;
            }
        }
    };
});

const formSignup = document.getElementById('signupForm');

formSignup.addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('usernameSignup').value; 
    const password = document.getElementById('passwordSignup').value;
    const csrfTokenSignup = document.getElementById('csrf-token-signup').value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './resources/php/signup.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('x-csrf-token', csrfTokenSignup);
    xhr.send(`usernameSignup=${encodeURIComponent(username)}&passwordSignup=${encodeURIComponent(password)}`); // Added '&' separator
    xhr.onload = function() {
        if (xhr.status === 200) { // Use strict equality
            const response = xhr.responseText;
            if (response === 'success') { // Use strict equality
                window.location.href = './home/home.php';
            } else {
                xhr.onerror = function() {
                    console.error('Request failed');
                    alert('An error occurred while processing your request. Please try again.');
                };
                document.getElementById("s-val-text").style.display = "flex";
                document.getElementById("s-val-msg").innerHTML = response;
            }
        }
    };
});
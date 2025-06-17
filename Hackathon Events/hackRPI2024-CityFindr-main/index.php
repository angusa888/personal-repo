<?php
    session_set_cookie_params([
        'lifetime' => 0, 
        'path' => '/',
        'domain' => '', 
        'secure' => true, 
        'httponly' => true, 
        'samesite' => 'Strict' 
    ]);
    session_start();
    
    $nonce = base64_encode(random_bytes(16));
    
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'nonce-$nonce'; style-src 'self' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; frame-ancestors 'self'; form-action 'self';");
    
    if (!isset($_SESSION['csrf-token-login'])) {
        $_SESSION['csrf-token-login'] = bin2hex(random_bytes(32));
    }
    if (!isset($_SESSION['csrf-token-signup'])) {
        $_SESSION['csrf-token-signup'] = bin2hex(random_bytes(32));
    }
    $csrfTokenLogin = $_SESSION['csrf-token-login'];
    $csrfTokenSignup = $_SESSION['csrf-token-signup'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" nonce="<?php echo $nonce; ?>"></script>
    <link rel="stylesheet" type="text/css" href="./resources/cityfindr.css">
    <link rel="stylesheet" type="text/css" href="./resources/index.css">
    <title>Log In</title>
</head>
<body>
<div class="container">
<button id="openModal1"><b>Login</b></button>
<button id="openModal2"><b>Sign Up</b></button>
    <div id="loginModal" class="modal">
        <div class="modal-content1">
            <span class="close">&times;</span>
            <h2>CityFindr</h2>

            <!-- Login form -->
            <form id="loginForm" action="login.php" method="POST">
                <input type="hidden" id="csrf-token-login" name="csrf-token-login" value="<?php echo $csrfTokenLogin; ?>">
                <input type="text" id="usernameLogin" name="usernameLogin" placeholder="Enter Username" required>
                <input type="password" id="passwordLogin" name="passwordLogin" placeholder="Enter Password" required>
                <button type="submit" class="signInBtn">Sign In</button>
            </form>
        </div>
    </div>
    <div id="signupModal" class="modal">
        <div class="modal-content2">
            <span class="close">&times;</span>
            <h2>Get Started with Us!</h2>
            <form id="signupForm" action="./resources/php/signup.php" method="POST">
                <input type="hidden" id="csrf-token-signup" name="csrf-token-signup" value="<?php echo $csrfTokenSignup; ?>">
                <input type="text" id="addressOne" placeholder="Enter your address line 1" required>
                <input type="text" id="addressTwo" placeholder="Enter your address line 2 (optional)">
                <input type="text" id="state" placeholder="Enter your state (if applicable)">
                <input type="text" id="city" placeholder="Enter your city" required>
                <input type="text" id="postalCode" placeholder="Enter your postal code" required>
                <input type="text" id="country" placeholder="Enter your country" required>
                <input type="text" id="usernameSignup" placeholder="Enter your username" required>
                <input type="text" id="email" placeholder="Enter your email" required>
                <input type="password" id="passwordSignup" placeholder="Enter Password" required>
                <button type="submit" class="signInBtn">Sign Up</button>
            </form>
        </div>
    </div>
    </div>
    <script src="./resources/login/login.js" nonce="<?php echo $nonce; ?>"></script>
</body>
</html>
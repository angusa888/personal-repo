<?php
session_set_cookie_params([
    'lifetime' => 0, 
    'path' => '/',
    'domain' => 'cerealkillers.eastus.cloudapp.azure.com', 
    'secure' => true, 
    'httponly' => true, 
    'samesite' => 'Strict' 
]);
session_start();
$nonce = base64_encode(random_bytes(16));

header('X-Content-Type-Options: nosniff');
header('Content-Type: text/html; charset=UTF-8');
header(header: "Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'nonce-$nonce'; style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; frame-ancestors 'self'; form-action 'self';");

if (!isset($_SESSION['csrf-token-login']) || empty($_SESSION['csrf-token-login'])) {
    $_SESSION['csrf-token-login'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['csrf-token-signup']) || empty($_SESSION['csrf-token-signup'])) {
    $_SESSION['csrf-token-signup'] = bin2hex(random_bytes(32));
}
$csrfTokenLogin = $_SESSION['csrf-token-login'];
$csrfTokenSignup = $_SESSION['csrf-token-signup'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./resources/login/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Philosopher&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="resources/Branding/logo.ico">
    <title>Garde Manger</title>
</head>
<body>
    <div class="container">
        <img class="the-logo" id="home-logo" src="./resources/stacked.svg" alt="logo">
        <div class="motto">
            <h3 class="motto-text"><span class="french">Bonjour</span> from your new kitchen assistant,</h3>
            <h3 class="motto-text"><span class="french">Au revoir</span> to your old cooking ways</h3>
        </div>
        <div class="info-items">
            <div class="info-item">
                <i class="bi bi-file-post-fill"></i>
                <p>Manage your pantry</p>
            </div>
            <div class="info-item">
                <i class="bi bi-card-text"></i>
                <p>Generate relevant recipes</p>
            </div>
            <div class="info-item">
                <i class="bi bi-basket"></i>
                <p>Get ready for groceries</p>
            </div>
        </div>
        <div class="authentication-buttons">
            <button id="openModal1"><b>Log in</b></button>
            <button id="openModal2"><b>Sign up</b></button>
        </div>
    </div>
    <div id="loginModal" class="modal">
            <div class="modal-content1">
                <span class="close">&times;</span>
                <h2>Garde Manger</h2>
                <div class="val-text" id="l-val-text"> 
                    <p class="val-msg" id="l-val-msg"></p>
                    <i class="bi bi-ban"></i>
                </div>
                <form id="loginForm" action="login.php" method="POST">
                    <input type="hidden" id="csrf-token-login" name="csrf-token-login" value="<?php echo $csrfTokenLogin; ?>">
                    <input type="text" id="usernameLogin" name="usernameLogin" placeholder="Username" required>
                    <input type="password" id="passwordLogin" name="passwordLogin" placeholder="Password" required>
                    <button type="submit" class="signInBtn">Log in</button>
                </form>
            </div>
        </div>

        <div id="signupModal" class="modal">
            <div class="modal-content2">
                <span class="close">&times;</span>
                <h2>Get Started with Us!</h2>
                <div class="val-text" id="s-val-text"> 
                    <p class="val-msg" id="s-val-msg"></p>
                    <i class="bi bi-ban"></i>
                </div>
                <form id="signupForm" action="./resources/php/signup.php" method="POST">
                    <input type="hidden" id="csrf-token-signup" name="csrf-token-signup" value="<?php echo $csrfTokenSignup; ?>">
                    <input type="text" id="usernameSignup" placeholder="Username" required>
                    <input type="password" id="passwordSignup" placeholder="Password" required>
                    <button type="submit" class="signInBtn">Sign up</button>
                </form>
            </div>
        </div>
    <script src="./resources/login/login.js" nonce="<?php echo $nonce; ?>"></script>
</body>
</html>
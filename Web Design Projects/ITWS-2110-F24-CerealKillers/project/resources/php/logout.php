<?php
session_start();

// Clear the session variables
$_SESSION = array();

// If session cookies are used, clear the cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Start a new session to regenerate the CSRF token
session_start();

// Redirect to the index page
header("Location: ../../index.php");
exit;
<?php

define('TIMEOUT', 1800); 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > TIMEOUT) || !isset($_SESSION['userId'])) {    
    session_unset();  
    session_destroy(); 
    header("Location: ../index.php");
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); 

?>

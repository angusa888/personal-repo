<?php
session_start(); // Start the session
require_once("./connection.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if(!isset($_SESSION['csrf-token-login']))
    {
        $_SESSION['csrf-token-login'] = $token;
    } 
    if (!hash_equals($_SESSION['csrf-token-login'], $token)) {
        header("Location: index.php?message=session_timeout");
        exit;
    }

    // Continue with login logic...
    $username = filter_input(INPUT_POST, 'usernameLogin', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'passwordLogin', FILTER_SANITIZE_STRING);

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare('SELECT Passwordhash FROM login WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the user exists and verify the password
    if ($row) {
        if (password_verify($password, $row['Passwordhash'])) {
            $stmt2 = $conn->prepare("SELECT userId FROM login WHERE username = ?");
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $_SESSION['userId'] = $row2['userId'];
            echo "success";
        } else {
            echo "Incorrect username or password.";
        }
    } else {
        echo "Incorrect username or password.";
    }
}
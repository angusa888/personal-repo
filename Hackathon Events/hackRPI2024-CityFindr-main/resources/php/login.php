<?php
session_start(); // Start the session
require_once("./connection.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the CSRF token from the X-CSRF-Token header
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf-token-login'] ?? '', $token)) {
        header("Location: index.php?message=session_timeout");
        exit;
    }

    // Sanitize and retrieve username and password
    $username = filter_input(INPUT_POST, 'usernameLogin', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'passwordLogin', FILTER_SANITIZE_STRING);

    // Prepare and execute the SQL statement to retrieve the password hash
    $stmt = $conn->prepare('SELECT passwordHash, userId FROM userlogin WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the user exists and verify the password
    if ($row) {
        if (password_verify($password, $row['passwordHash'])) {
            // Store user ID in session
            $_SESSION['userId'] = $row['userId'];
            echo "success"; // Login successful
        } else {
            echo "Incorrect password"; // Password mismatch
        }
    } else {
        echo "Incorrect username"; // Username not found
    }
}
?>
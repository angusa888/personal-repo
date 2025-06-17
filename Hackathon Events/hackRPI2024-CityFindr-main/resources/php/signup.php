<?php
session_start();
require_once("./connection.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION['csrf-token-signup'], $token)) {
        header("Location: index.php?message=session_timeout");
        exit;
    }
    // Initialize variables
    $username = $_POST['usernameSignup'] ?? '';
    $password = $_POST['passwordSignup'] ?? '';
    $addressOne = $_POST['addressOne'] ?? '';
    $addressTwo = $_POST['addressTwo'] ?? '';
    
    $state = $_POST['state'] ?? '';
    $postalCode = $_POST['postalCode'] ?? '';
    $country = $_POST['country'] ?? '';
    $city = $_POST['city'] ?? '';
    $email = $_POST['email'] ?? '';

    $streetAddress = trim($addressOne . (!empty($addressTwo) ? ', ' . $addressTwo : ''));
    // Check for valid username format (e.g., alphanumeric, length)
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        echo "Username must be alphanumeric and between 3 to 20 characters.";
        exit;
    }

    // Rate limiting (you would need to implement a mechanism for tracking attempts)

    // Check if username already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM userlogin WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Username already taken.";
        exit;
    }

    // Hash the password securely
    $options = [
        'memory_cost' => 1 << 17, // 128 MB
        'time_cost' => 4,
        'threads' => 2,
    ];
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID, $options);

    try {
        // Use prepared statement to insert the new user
        $stmt = $conn->prepare("INSERT INTO userlogin (username, email, passwordHash, streetAddress, city, state, postalCode, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $email, $hashed_password, $streetAddress, $city, $state, $postalCode, $country);
        $stmt->execute();
        $stmt2 = $conn->prepare("SELECT userId FROM userlogin WHERE username = ?");
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $_SESSION['userId'] = $row2['userId'];
        echo "success";
    } catch (Exception $e) {
        // Log the error securely and show a generic message
        error_log($e->getMessage());
        echo "An error occurred. Please try again.";
    }
}
    
<?php
session_start();
require_once("./connection.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if(!isset($_SESSION['csrf-token-signup']))
    {
        $_SESSION['csrf-token-signup'] = $token;
    } 
    if (!hash_equals($_SESSION['csrf-token-signup'], $token)) {
        header("Location: index.php?message=session_timeout");
        exit;
    }

    $username = $_POST['usernameSignup'] ?? '';
    $password = $_POST['passwordSignup'] ?? '';

    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        echo "Username must be alphanumeric and between 3 to 20 characters.";
        exit;
    }


    $stmt = $conn->prepare("SELECT COUNT(*) FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Username already taken.";
        exit;
    }

    $options = [
        'memory_cost' => 1 << 17, // 128 MB
        'time_cost' => 4,
        'threads' => 2,
    ];
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID, $options);

    try {
        $stmt = $conn->prepare("INSERT INTO login (username, passwordhash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        $stmt2 = $conn->prepare("SELECT userId FROM login WHERE username = ?");
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $_SESSION['userId'] = $row2['userId'];
        echo "success";
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "An error occurred. Please try again.";
    }
}
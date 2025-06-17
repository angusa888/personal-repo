<?php
session_start();
require_once('./connection.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the old and new passwords from the form
    $oldPassword = isset($_POST['oldPassword']) ? trim($_POST['oldPassword']) : '';
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';

    // Check if userId is set in the session
    if (!isset($_SESSION['userId'])) {
        echo "User  not logged in.";
        exit;
    }

    // Assuming you have a user session with the user's ID
    $userId = $_SESSION['userId']; // Replace with your actual session variable for user ID

    // Fetch the user's current password from the database
    $stmt = $conn->prepare("SELECT passwordHash FROM userlogin WHERE userId = ?");
    
    // Check if prepare failed
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("i", $userId); // "i" indicates the type is integer
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists
    if (!$user) {
        echo "User  not found.";
        exit;
    }
    // Verify the old password
    if (password_verify($oldPassword, $user['passwordHash'])) {
        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_ARGON2ID); // No options needed unless you have specific ones
        // Update the password in the database
        $updateStmt = $conn->prepare("UPDATE userlogin SET passwordHash = ? WHERE userId = ?");
        
        // Check if prepare failed
        if ($updateStmt === false) {
            echo "Error preparing update statement: " . $conn->error;
            exit;
        }

        $updateStmt->bind_param("si", $hashedNewPassword, $userId); // "si" indicates types: string and integer
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "Password changed successfully!";
        } else {
            echo "No changes made or error occurred.";
        }
    } else {
        echo "Old password is incorrect.";
    }

    // Close the statements
    $stmt->close();
    if (isset($updateStmt)) {
        $updateStmt->close();
    }
}

// Close the database connection
$conn->close();
?>
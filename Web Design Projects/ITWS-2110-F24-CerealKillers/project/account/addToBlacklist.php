<?php
session_start();

require_once('../resources/php/connection.php');

$ingredient = $_POST['myIngredient'];
$userId = $_SESSION['userId'];

$sql = "SELECT blacklist FROM users_pantry WHERE userId = ?";

$checkSql = "SELECT COUNT(*) FROM users_pantry WHERE userId = ?";
    
if ($checkStmt = $conn->prepare($checkSql)) {
    // Bind the userId parameter
    $checkStmt->bind_param("i", $userId); 
    
    // Execute the statement
    $checkStmt->execute();
    
    // Bind the result variable
    $checkStmt->bind_result($count); 
    
    // Fetch the result
    $checkStmt->fetch();
    
    // Close the check statement
    $checkStmt->close();
    
    // If userId is not found, insert a new row
    if ($count == 0) {
        // Prepare the insert statement
        $insertSql = "INSERT INTO users_pantry (userId) VALUES (?)";
        
        if ($insertStmt = $conn->prepare($insertSql)) {
            // Bind the parameters
            $insertStmt->bind_param("i", $userId); 
            
            // Execute the insert statement
            if ($insertStmt->execute()) {
            } else {
                echo '<p>Error inserting row: ' . htmlspecialchars($conn->error) . '</p>'; // Error handling
            }
            
            // Close the insert statement
            $insertStmt->close();
        }
    }
}
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userId); 
    $stmt->execute();
    $stmt->bind_result($oldBlacklist); 
    $stmt->fetch();
    $stmt->close();
} 

$blacklistArray = json_decode($oldBlacklist, true);

if ($blacklistArray === null) {
    $blacklistArray = [];
}

if (!in_array($ingredient, $blacklistArray)) {
    $blacklistArray[] = $ingredient; 
}

$finalStringBlacklist = json_encode($blacklistArray);

$updateSQL = "UPDATE users_pantry SET blacklist = ? WHERE userId = ?";
if ($updateStmt = $conn->prepare($updateSQL)) {
    $updateStmt->bind_param("si", $finalStringBlacklist, $userId); // Bind parameters
    $updateStmt->execute();
    echo("Blacklist item added successfully!");
    $updateStmt->close();
} else {
    die("Error: " . $conn->error);
}

$conn->close();
?>
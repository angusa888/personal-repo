<?php
session_start();

require_once('../resources/php/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {   
    
    $ingredient = $_POST['ingredient'];
    $userId = $_SESSION['userId'];

    $sql = "SELECT blacklist FROM users_pantry WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $blacklist = $result->fetch_assoc();
        
        if ($blacklist) {
            $oldBlacklist = json_decode($blacklist['blacklist'], true); // Decode JSON to array

            if (is_array($oldBlacklist) && in_array($ingredient, $oldBlacklist)) {
                $oldBlacklist = array_diff($oldBlacklist, [$ingredient]); // Remove the ingredient
            }

            $newBlacklist = json_encode(array_values($oldBlacklist)); 

            $updateSQL = "UPDATE users_pantry SET blacklist = ? WHERE userId = ?";
            $updateStmt = $conn->prepare($updateSQL);
            $updateStmt->bind_param("si", $newBlacklist, $userId);

            if ($updateStmt->execute()) {
                echo("Blacklist item removed successfully!");
            } else {
                die("Error: " . $updateStmt->error);
            }
        } else {
            die("No blacklist found for user.");
        }
    } else {
        die("Error. Couldn't get original blacklist: " . $stmt->error);
    }
}
?>
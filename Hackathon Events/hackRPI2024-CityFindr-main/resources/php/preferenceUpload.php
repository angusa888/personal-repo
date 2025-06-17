<?php
session_start();
require_once("./connection.php");

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get userId from session
$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if preferences is set
    $preferences = filter_input(INPUT_POST, 'preferences', FILTER_SANITIZE_STRING);
    $preferences = strtolower($preferences);
    
    if ($preferences) {
        // Prepare the SQL query to select existing preferences
        $checkStmt = $conn->prepare("SELECT preferences FROM userProfile WHERE userId = ?");
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // Check if preferences exist for the user
        if ($result->num_rows == 0) {
            // If no preferences exist, insert a new record
            $stmt = $conn->prepare("INSERT INTO userProfile (userId, preferences) VALUES (?, ?)");
            $preferencesJson = json_encode([$preferences]); // Store as an array of preferences
            
            if ($stmt) {
                $stmt->bind_param("is", $userId, $preferencesJson);
                if ($stmt->execute()) {
                    echo "Preferences uploaded successfully.";
                } else {
                    echo "Error uploading preferences: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            // If preferences already exist, check if the new preference is already present
            $existingRow = $result->fetch_assoc();
            $existingPreferences = json_decode($existingRow['preferences'], true);
            
            // Check if the new preference already exists
            if (in_array($preferences, $existingPreferences)) {
                echo "This preference already exists.";
            } else {
                // Add new preference to existing ones
                $existingPreferences[] = $preferences; // Append the new preference
                
                // Prepare the SQL statement to update preferences
                $stmt = $conn->prepare("UPDATE userProfile SET preferences = ? WHERE userId = ?");
                $preferencesJson = json_encode($existingPreferences); // Convert back to JSON
                
                if ($stmt) {
                    $stmt->bind_param("si", $preferencesJson, $userId);
                    if ($stmt->execute()) {
                        echo "Preferences updated successfully.";
                    } else {
                        echo "Error updating preferences: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            }
        }

        // Fetch user preferences to handle tags
        $userPreferences = [];
        $result->data_seek(0); // Reset result pointer to the beginning
        while ($row = $result->fetch_assoc()) {
            $userPreferences = array_merge($userPreferences, json_decode($row['preferences'], true));
        }
        
        $uniqueTags = array_unique(array_filter(array_map('trim', $userPreferences))); // Clean and get unique tags
        foreach ($uniqueTags as $tag) {
            if (!empty($tag)) {
                // Prepare an insert statement for tags
                $stmt = $conn->prepare("INSERT IGNORE INTO tags (tag) VALUES (?)");
                if ($stmt) {
                    $stmt->bind_param("s", $tag);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "Error preparing statement for tag insertion: " . $conn->error;
                }
            }
        }

        $checkStmt->close(); // Close the check statement
    } else {
        echo "No preferences provided.";
    }
}
?>
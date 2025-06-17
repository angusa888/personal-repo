<?php
session_start();

require_once('../resources/php/connection.php');
$userId = $_SESSION['userId'];
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
            $insertStmt->bind_param("i", $userId); 
            
            if ($insertStmt->execute()) {
            } else {
                echo '<p>Error inserting row: ' . htmlspecialchars($conn->error) . '</p>'; // Error handling
            }
            
            // Close the insert statement
            $insertStmt->close();
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['dietaryForm'])) {

        $dietaryForm = $_POST['dietaryForm'];
        $toBlacklist = array();

        $sql = "SELECT blacklist FROM users_pantry WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $blacklistResult = $result->fetch_assoc();
            
            if ($blacklistResult != null) {
                // Decode the JSON and ensure it's an array
                $blacklist = json_decode($blacklistResult['blacklist'], true);
                if (!is_array($blacklist)) {
                    $blacklist = array(); // Initialize as an empty array if decoding fails
                }
            } else {
                $blacklist = array(); // Initialize as an empty array if no result
            }
        } else {
            die("Error. Couldn't get original blacklist: " . $stmt->error);
        }

        foreach ($dietaryForm as $preset) {
            if ($preset == "vegetarian") {
                $meatSQL = "SELECT ingredient FROM recipeIngredients WHERE type = 'meat'";
                $meatStmt = $conn->prepare($meatSQL);
                if ($meatStmt->execute()) {
                    $meats = $meatStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    foreach ($meats as $meat) {
                        $ingredient = $meat['ingredient'];
                        if (!in_array($ingredient, $blacklist, true)) { 
                            $blacklist[] = $ingredient; // Add the ingredient string
                        }
                    }
                } else {
                    die("Error: " . $meatStmt->error);
                }
            }
            if ($preset == "vegan") {
                $toBlacklist[] = 'eggs';
                $meatDairySQL = "SELECT ingredient FROM recipeIngredients WHERE type = 'meat' OR type = 'dairy'";
                $meatDairyStmt = $conn->prepare($meatDairySQL);
                if ($meatDairyStmt->execute()) {
                    $meatsDairy = $meatDairyStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    foreach ($meatsDairy as $item) {
                        if (!in_array($item['ingredient'], $blacklist)) { // Check the ingredient field
                            $blacklist[] = $item['ingredient']; // Add the ingredient string
                        }
                    }
                } else {
                    die("Error: " . $meatDairyStmt->error);
                }
            }

            if ($preset == "nuts") {
                $nutSQL = "SELECT ingredient FROM recipeIngredients WHERE type = 'nut'";
                $nutStmt = $conn->prepare($nutSQL);
                if ($nutStmt->execute()) {
                    $nuts = $nutStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    foreach ($nuts as $nut) {
                        if (!in_array($nut['ingredient'], $blacklist)) { // Check the ingredient field
                            $blacklist[] = $nut['ingredient']; // Add the ingredient string
                        }
                    }
                } else {
                    die("Error: " . $nutStmt->error);
                }
            }

            if ($preset == "shellfish") {
                $shellfishArray = array('shrimp', 'jumbo shrimp', 'scallops', 'manila clams');
                $blacklist = array_merge($blacklist, $shellfishArray);
            }

            if ($preset == "soys") {
                $soyArray = array('soy sauce', 'white miso', 'tofu');
                $blacklist = array_merge($blacklist, $soyArray);
            }
        }

        $blacklistJson = json_encode($blacklist); // Encode the blacklist to JSON

        $updateSQL = "UPDATE users_pantry SET blacklist = ? WHERE userId = ?";
        $updateStmt = $conn->prepare($updateSQL);
        $updateStmt->bind_param("si", $blacklistJson, $userId); 
        
        if ($updateStmt->execute()) {
            echo("Preset added successfully!");
        } else {
            die("Error: " . $updateStmt->error);
        }
    } else {
        echo("You didn't select a preset!");
    }
} 
?>
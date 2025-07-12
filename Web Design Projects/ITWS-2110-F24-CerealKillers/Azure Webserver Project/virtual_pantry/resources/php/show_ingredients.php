<?php
$meatArray = []; 
$legumeArray = []; 
$dairyArray = [];                      
$liquidArray = []; 
$snackArray = []; 
$vegetableArray = []; 
$fruitArray = []; 
$condimentArray = []; 
$mealArray = []; 
$spiceArray = []; 
$grainArray = []; 
$otherArray = []; 
$stapleArray = [];

// Check if the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    
    // Prepare statement to get ingredients from users_pantry
    if ($stmt = $conn->prepare("SELECT ingredients FROM users_pantry WHERE userId = ?")) {
        $stmt->bind_param("i", $userId);
        
        // Execute the statement
        if (!$stmt->execute()) {
            echo "Error executing query: " . htmlspecialchars($stmt->error);
        } else {
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($ingredientJson);
                while ($stmt->fetch()) {
                    if($ingredientJson !== null)
                    {
                        $ingredientsArray = json_decode($ingredientJson, true);
                    
                     if (json_last_error() !== JSON_ERROR_NONE) {
                        echo "JSON decoding error: " . json_last_error_msg();
                        continue; 
                    }
                        foreach ($ingredientsArray as $ingredient) {
                            if ($stmt2 = $conn->prepare("SELECT type FROM recipeIngredients WHERE ingredient = ?")) {
                                $stmt2->bind_param("s", $ingredient);
                                
                                // Execute the statement
                                if (!$stmt2->execute()) {
                                    echo "Error executing query for ingredient type: " . htmlspecialchars($stmt2->error);
                                } else {
                                    $stmt2->store_result();
    
                                    if ($stmt2->num_rows > 0) {
                                        $stmt2->bind_result($type);
                                        while ($stmt2->fetch()) {
                                            switch ($type) {
                                                case 'meat':
                                                    $meatArray[] = $ingredient;
                                                    break;
                                                case 'legume':
                                                    $legumeArray[] = $ingredient;
                                                    break;
                                                case 'dairy':
                                                    $dairyArray[] = $ingredient;
                                                    break;
                                                case 'liquid':
                                                    $liquidArray[] = $ingredient;
                                                    break;
                                                case 'staple':
                                                    $stapleArray[] = $ingredient;
                                                    break;
                                                case 'snack':
                                                    $snackArray[] = $ingredient;
                                                    break;
                                                case 'vegetable':
                                                    $vegetableArray[] = $ingredient;
                                                    break;
                                                case 'fruit':
                                                    $fruitArray[] = $ingredient;
                                                    break;
                                                case 'condiment':
                                                    $condimentArray[] = $ingredient;
                                                    break;
                                                case 'meal':
                                                    $mealArray[] = $ingredient;
                                                    break;
                                                case 'spice':
                                                    $spiceArray[] = $ingredient;
                                                    break;
                                                case 'grain':
                                                    $grainArray[] = $ingredient;
                                                    break;
                                                default:
                                                    $otherArray[] = $ingredient; 
                                                    break;
                                            }
                                        }
                                    } else {
                                        // If no type found, add to other
                                        $otherArray[] = $ingredient; 
                                    }
                                }
                                $stmt2->close(); 
                            } else {
                                echo "Error preparing statement for ingredient type: " . htmlspecialchars($conn->error);
                            }
                        }
                    }
                }
            } 
        }
        $stmt->close();
    } else {
        echo "Error preparing statement for users_pantry: " . htmlspecialchars($conn->error);
    }
} else {
    echo "User  ID not set in session.";
}
?>
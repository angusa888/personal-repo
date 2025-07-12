<?php
session_start();
require_once("../../../resources/php/connection.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['myIngredient'])) {
        $newIngredient = trim($_POST['myIngredient']);
        if (!empty($newIngredient)) {
            $stmt = $conn->prepare("SELECT ingredients FROM users_pantry WHERE userId = ?");
            $stmt->bind_param("i", $_SESSION['userId']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $existingIngredients = $result->fetch_assoc()['ingredients'];
                $ingredientsArray = json_decode($existingIngredients, true);

                if (!in_array($newIngredient, $ingredientsArray)) {
                    $ingredientsArray[] = $newIngredient;
                    $updatedIngredients = json_encode($ingredientsArray);

                    $updateStmt = $conn->prepare("UPDATE users_pantry SET ingredients = ? WHERE userId = ?");
                    $updateStmt->bind_param("si", $updatedIngredients, $_SESSION['userId']);
                    $updateStmt->execute();

                    if ($updateStmt->affected_rows > 0) {
                        echo "Ingredient added successfully!";
                    } else {
                        echo "Failed to add ingredient.";
                    }
                    $updateStmt->close();
                } else {
                    echo "Ingredient already exists.";
                }
            } else {
                $ingredientsArray = [$newIngredient];
                $newIngredientJSON = json_encode($ingredientsArray);

                $insertStmt = $conn->prepare("INSERT INTO users_pantry (userId, ingredients) VALUES (?, ?)");
                $insertStmt->bind_param("is", $_SESSION['userId'], $newIngredientJSON);
                if ($insertStmt->execute()) {
                    echo "Ingredient added successfully!";
                } else {
                    echo "Failed to create new pantry.";
                }
                $insertStmt->close();
            }
            $stmt->close();
        } else {
            echo "No ingredient provided.";
        }
    } else {
        echo "Invalid request method.";
    }
}
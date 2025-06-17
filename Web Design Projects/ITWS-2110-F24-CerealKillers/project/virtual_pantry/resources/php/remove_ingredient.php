<?php
session_start();
require_once '../../../resources/php/connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ingredientToRemove = trim($_POST['ingredient']);
    $userId = $_SESSION['userId'];
    function removeIngredientFromPantry($userId, $ingredientToRemove, $conn) {
        $query = "SELECT ingredients FROM users_pantry WHERE userId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ingredientsJson = $row['ingredients'];
            $ingredientsArray = json_decode($ingredientsJson, true);
            $key = array_search($ingredientToRemove, $ingredientsArray);
            $key = array_search($ingredientToRemove, $ingredientsArray);
            if ($key !== false) {
                unset($ingredientsArray[$key]);
            }
            $updatedIngredientsJson = json_encode (array_values($ingredientsArray));
            $updateStmt = $conn->prepare("UPDATE users_pantry SET ingredients = ? WHERE userId = ?");
            $updateStmt->bind_param("si", $updatedIngredientsJson, $userId);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                echo "Ingredient removed successfully!";
            }
            else {
                echo "Failed to update pantry.";
            }
            $updateStmt->close();
        }
        else {
            echo "No ingredients found for this user.";
        }
        $stmt->close();
    }
    removeIngredientFromPantry($userId, $ingredientToRemove, $conn);
}
$conn->close();

?>
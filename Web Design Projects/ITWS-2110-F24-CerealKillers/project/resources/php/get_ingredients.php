<?php
require_once("./connection.php");

$query = "SELECT * FROM recipeIngredients";
$result = $conn->query($query);

$ingredients = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ingredients[] = $row;
    }
}
$ingredients = array_map(function($ingredient) {
    return $ingredient['ingredient'];
}, $ingredients);
header('Content-Type: application/json');
echo json_encode($ingredients);

?>

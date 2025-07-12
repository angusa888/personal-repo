<?php

// The RecipeIngredients Table Has Already Been Set Up During -- FUNCTIONB -- Do Not Uncomment This Code, Otherwise Duplicate Entries Will Appear In The Table
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// !!!!!!!!!!!!!!!!!! ! ! !  ! ! !  ! !!!!!!!!!!!! ! ! ! ! ! ! !!  !! !  !!!!!!!!!!!!  ! ! ! ! !  ! !!!!!!!!!!!!!!! ! ! !  ! !  ! !   !  ! !  ! ! !! !  ! ! ! !

// $conn = new mysqli('localhost', 'phpmyadmin', 'Fireworks&laundry8', 'pantryDB');

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $recipe_ingredients_arr = [];

// $stmt = $conn->prepare("SELECT `name`, `ingredients` FROM recipes");
// $stmt->execute();
// $result = $stmt->get_result();

// while ($recipe = $result->fetch_assoc()) {
//    $name = $recipe['name'];
//    $ingredients = json_decode($recipe['ingredients']);
//    foreach ($ingredients as $recipe_ingredient) {
//       if (!isset($recipe_ingredients_arr[$recipe_ingredient])) {
//          $recipe_ingredients_arr[$recipe_ingredient] = [];
//       }
//       array_push($recipe_ingredients_arr[$recipe_ingredient], $name);
//    }
// }

// $stmt->close();

// $ingredient = "";
// $recipes = "";

// $stmt = $conn->prepare("INSERT INTO recipeIngredients (`ingredient`, `recipes`) VALUES (?, ?)");
// $stmt->bind_param("ss", $ingredient, $recipes);

// foreach (array_keys($recipe_ingredients_arr) as $recipe_ingredient) {
//     $ingredient = $recipe_ingredient;
//     $recipes = json_encode($recipe_ingredients_arr[$recipe_ingredient]);
//     $stmt->execute();
// }

// $stmt->close();
// $conn->close();

?>

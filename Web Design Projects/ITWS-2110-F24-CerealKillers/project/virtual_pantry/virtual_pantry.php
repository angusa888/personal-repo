<?php
require_once("../resources/php/connection.php");
session_start();
include '../resources/php/session_check.php';

$userId = $_SESSION['userId'];
include './resources/php/show_ingredients.php';

header("Content-Type: text/html; charset=UTF-8");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="../resources/project.css">
    <link rel="stylesheet" type="text/css" href="./resources/css/pantry.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
    <link rel="icon" href="../resources/Branding/logo.ico">
    <link href="../resources/Branding/Branding.css" rel="stylesheet" type="text/css">
    <title>Virtual Pantry</title>
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg custom-navbar fixed-top" data-bs-theme="dark">
         <div class="container-fluid">
            <a class="navbar-brand gradient-text" href="../home/home.php">
                  <img src="../resources/garde.svg" alt="Logo" height="40" class="d-inline-block align-text-top logo-nav">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
               <ul class="navbar-nav me-auto">
                  <li class="nav-item">
                     <a class="nav-link" href=../home/home.php>Home
                        <span class="visually-hidden">(current)</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a id="active-on-page" class="nav-link active" href="#">My Pantry</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="../account/account.php">Account</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="../add_recipe.php">Submit My Own Recipe</a>
                  </li>
               </ul>
            </div>
            <form id="logoutForm" action="../resources/php/logout.php" method="POST">
                <button id="logout" class="btn btn-secondary my-2 my-sm-1" type="submit">Log Out</button>
            </form>
         </div>
      </nav>

<div class="container">
    <div class="content-box text-center">
        <h2 class="">Please Select Ingredients for the Recipe Generator!</h2>
    </div>
</div>

<div id="add-ingredient-container" class="container my-4 position-relative">
    <form id="addIngredientForm" autocomplete="off" action="./resources/php/add_ingredient.php" method="POST" class="text-center pantry-add-ingredient">
        <div class="autocomplete mb-3 ingredient-form" style="width: 300px;">
            <input id="myInput" type="text" name="myIngredient" class="form-control" placeholder="Ingredient name">
        </div>
        <input type="submit" class="btn btn-mega-blue" value="Add Ingredient to Pantry">
    </form>
</div>

<div class="container my-4 position-relative">
    <form action="" method="post" id = "ingredientsList">
        <div class="row">
            <?php
            $categories = [
                'Meats' => $meatArray,
                'Grains' => $grainArray,
                'Legumes' => $legumeArray,
                'Vegetables' => $vegetableArray,
                'Fruits' => $fruitArray,
                'Snacks' => $snackArray,
                'Liquids' => $liquidArray,
                'Spices' => $spiceArray,
                'Condiments' => $condimentArray,
                'Staples' => $stapleArray,
                'Meals' => $mealArray,
                'Dairy' => $dairyArray,
                'Other' => $otherArray,
            ];
            foreach ($categories as $category => $items) {
                echo '<div class="col-md-4 mb-4">';
                echo "<h3>$category</h3><div class='border p-3'>";
                if (count($items) == 0) {
                    echo "<p>No $category items!</p>";
                } else {
                    foreach ($items as $ingredient) {
                        $ingredientId = htmlspecialchars($ingredient);
                        echo '<div class="form-check d-flex align-items-center" style="margin-bottom: 5px;">';
                        echo '<input class="form-check-input me-2" type="checkbox" name="formIngredients[]" id="' . $ingredientId . '" value="' . htmlspecialchars($ingredient) . '">';
                        echo '<label class="form-check-label me-2" for="' . $ingredientId . '">' . htmlspecialchars($ingredient) . '</label>';
                        echo '<span class="close-mark" style="cursor:pointer; color:var(--mega-blue); font-weight:bold; margin-left:5px;" onclick="removeItem(\'' . addslashes(htmlspecialchars($ingredient)) . '\')">&times;</span>';
                        echo '</div>';
                    }
                }
                echo '</div></div>';
            }
            ?>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-mega-blue" data-bs-toggle="modal" data-bs-target="#recipeModal">Generate Recipes</button>
            </div>
        </div>
    </form>
    
    <!-- Recipes -->
    <div class="modal fade" id="recipeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role = "dialog">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-bottom-0">
                    <h2 class="fw-bold mb-0">Recipes Generated</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="d-grid gap-4 list-unstyled small" id = "ulModal">
                        <
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br>
    
<script src="./resources/js/pantry.js"></script>
<script> 
    document.getElementById('ingredientsList').addEventListener('submit', function(event) {
        event.preventDefault();
            
        // Gather form data
        const formData = new FormData(this);
        
        // Send form data to generateRecipes.php using fetch
        fetch('./resources/php/generateRecipes.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => displayRecipes(data))  
        .catch(error => console.error('Error:', error));  
    });
</script>

<script src="../resources/autocomplete.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("../resources/php/get_ingredients.php")
                .then((response) => response.json())
                .then((data) => {
                    // console.log("Ingredients fetched from DB:", data);
                    const ingredients = data.map(item => item.ingredient_name);
                // console.log("Ingredients:", data);
                autocomplete(document.getElementById("myInput"), data);
            });
        });
    </script>
</body>

</html>
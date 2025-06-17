<?php
   session_start();
   include '../resources/php/session_check.php';
   $nonce = base64_encode(random_bytes(16));

   header("X-Content-Type-Options: nosniff");
   header("Content-Type: text/html; charset=UTF-8");
   
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>My Garde Manger</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link href="../resources/Branding/Branding.css" rel="stylesheet" type="text/css">
      <link rel="icon" href="../resources/Branding/logo.ico">
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
                     <a class="nav-link" href="../virtual_pantry/virtual_pantry.php">My Pantry</a>
                  </li>
                  <li class="nav-item">
                     <a id="active-on-page" class="nav-link active" href="#">Account</a>
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
      <!-- Display user's blacklisted ingredients -->
      <div id = "showBlacklist" class="container">
         <h2 class="section-title">Your blacklisted ingredients:</h2>
         <br>
         <?php
            echo '<div class="scrollable-card-container user-blacklist-container">';
            include "./displayBlacklist.php";
            echo '</div>'
         ?>
      </div>
      <!-- Form to edit dietary restrictions -->
      <div id = "editRestrictions" class="container position-relative">
         <div>
            <h2 class="section-title">Common dietary restrictions:</h2> 
            <p class="agreement-text"><em>By selecting these preferences, we will not show you recipes that include the ingredients you select here. </em></p>
            <p class="agreement-text"><em>If you wish to see all recipes and make your own substitutions, do not select any preferences.</em></p>
            <div id = "addIngredient" class="">
            <div class="val-text" id="b-val-text"> 
                    <p class="val-msg" id="b-val-msg"></p>
            </div>
            <form id="addIngredientForm" autocomplete="off">
                <div class="autocomplete" style="width: 300px;" id ="smallDiv">
                    <input id="myInput" type="text" name="myIngredient" class="form-control" placeholder="Ingredient name">
                </div>
                <input type="submit" class="btn btn-mega-blue" value="Add Ingredient to Blacklist" id = "addBlacklist">
            </form>
            </div>
            <br>
            <form name="dietaryRestrictionsForm" id ="dietaryList">
               <div id="preferences-card" class="card">
                  <div class="dietary-restrictions-options">
                     <input id="vegetarian" type="checkbox" name="dietaryForm[]" value="vegetarian">
                     <label for="vegetarian">Vegetarian</label>
                     <br>
                     <input id="vegan" type="checkbox" name="dietaryForm[]" value="vegan">
                     <label for="vegan">Vegan</label>
                     <br>
                     <input id="nuts" type="checkbox" name="dietaryForm[]" value="nuts">
                     <label for="nuts">Nut Allergy</label>
                     <br>
                     <input id="shellfish" type="checkbox" name="dietaryForm[]" value="shellfish">
                     <label for="shellfish">Shellfish Allergy</label>
                     <br>
                     <input id="soys" type="checkbox" name="dietaryForm[]" value="soys">
                     <label for="soys">Soy Allergy</label>
                     <br>
                  </div>
               </div>
               <input type="submit" class="btn btn-mega-blue" value="Set Preferences" id="submit">
            </form>
         </div> 
      </div>
         <!-- Form to add an ingredient to the blacklist -->
      <script src="../resources/autocomplete.js" nonce="<?php echo $nonce; ?>"></script>
      <script nonce="<?php echo $nonce; ?>">
         document.addEventListener("DOMContentLoaded", function() {
            fetch("../resources/php/get_ingredients.php")
               .then((response) => response.json())
               .then((data) => {
                     const ingredients = data.map(item => item.ingredient_name);
               autocomplete(document.getElementById("myInput"), data);
            });
         });
   </script>
   <script src = "account.js" nonce="<?php echo $nonce; ?>"></script>
   </body>
</html>
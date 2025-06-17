<?php 
session_start();
$userId = $_SESSION['userId'];

/* Data Structures Class */
// Make sure the DS extension is enabled
if (!extension_loaded('ds')) {
    die("DS extension is not enabled.");
}

use Ds\Map;

function recipeIngredientsMap($map, $recipesIngr) {
    // Populate map
    for ($i = 0; $i < count($recipesIngr); $i++) {
        //Get recipes for current ingredient
        $recipes = json_decode($recipesIngr[$i]["recipes"], true);

        // If decoding was successful and $recipes is an array
        if (is_array($recipes)) {
            for ($j = 0; $j < count($recipes); $j++) {
                $recipe = $recipes[$j];
                // Check if recipe exists in the map, if it does, append the key to the array.
                if ($map->hasKey($recipe)) {
                    $ingredients = $map->get($recipe);
                    $ingredients[] = $recipesIngr[$i]["ingredient"];
                    $map->put($recipe, array_unique($ingredients));
                } else {
                    $map->put($recipe, [$recipesIngr[$i]["ingredient"]]);
                }
            }
        }
    }

    // Sort map by length of value array (number of ingredients)
    $map->sort(function($a, $b) {
        return count($b) - count($a);
    });
    return $map;
}

function totalIngredientsArray($fullRecipes) {
    $totalArray = array();
    for ($i = 0; $i < count($fullRecipes); $i++) {
        $key = $fullRecipes[$i]["name"];
        $ingredients = json_decode($fullRecipes[$i]["ingredients"], true);
        $totalIngredients = count($ingredients);
        $totalArray[$key] = $totalIngredients;
    }
    return $totalArray;
}

/* Database stuff and excecuting the functions */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = "localhost";
$dbname = "pantryDB";
$username = "phpmyadmin";
$password = "Fireworks&laundry8";

// Set isBlacklist boolean
$isBlacklist = False;

// Connect to MySQL database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $formIngr = $_POST['formIngredients'];

    // Get the user's current blacklist
    $blacklistSQL = "SELECT blacklist FROM users_pantry WHERE userId = $userId";
        
    // Excecute the query
    try {
        $blacklistStmt = $pdo->prepare($blacklistSQL);
        $blacklistStmt->execute();
        $blacklistResponse = $blacklistStmt->fetchAll(PDO::FETCH_DEFAULT);
        if(count($blacklistResponse) != 0) {
            $blacklist = $blacklistResponse[0]["blacklist"];
            $isBlacklist = True;
        }
    } catch (PDOException $e) {
        die("Error. Couldn't get blacklist: " . $e->getMessage());
    }

    if(!empty($formIngr)) { 
        /* Get ingredients selected by users */
        $placeholders = rtrim(str_repeat('?,', count($formIngr)), ',');
        
        // Prepare SQL with dynamic placeholders
        $sql = "SELECT * FROM recipeIngredients WHERE ingredient IN ($placeholders)";

        // Execute the query
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($formIngr); // Pass the array directly to execute()

            $recipesIngr = $stmt->fetchAll(PDO::FETCH_ASSOC);

            /* Map each recipe as a value and the ingredients as the value */
            $map = new Map();
            $map = recipeIngredientsMap($map, $recipesIngr);

            /* Get all of the recipe information and sort the map even more */
            $mapRecipes = $map->keys();
            $recipeArray = $mapRecipes->toArray();
            $placeholders2 = rtrim(str_repeat('?,', count($recipeArray)), ',');
            $sql2 = "SELECT * FROM recipes WHERE name IN ($placeholders2)";

            // Excecute the query
            try {
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute($recipeArray);

                $fullRecipes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                usort($fullRecipes, function ($a, $b) use ($recipeArray) {
                    $indexA = array_search($a['name'], $recipeArray);
                    $indexB = array_search($b['name'], $recipeArray);
                    
                    // If the name is not found in $recipeArray, set a high index to push it to the end
                    if ($indexA === false) $indexA = PHP_INT_MAX;
                    if ($indexB === false) $indexB = PHP_INT_MAX;
                
                    return $indexA - $indexB;
                });

                // Remove all recipes that have blacklisted ingredients
                if($isBlacklist == True) {
                    $blacklistArr = json_decode($blacklist, true);
                    $recipesNoBlacklist = array();
                    
                    foreach ($fullRecipes as $indvRecipe) {
                        $ingredientString = $indvRecipe["ingredients"]; // Get the ingredient string
                        $ingredientArray = json_decode($ingredientString, true); // Convert ingredients to an array
                    
                        // Check if any blacklisted ingredient is in the recipe's ingredients
                        $containsBlacklisted = false;
                        foreach ($blacklistArr as $blacklisted) {
                            if (in_array($blacklisted, $ingredientArray)) {
                                $containsBlacklisted = true;
                                break; 
                            }
                        }
                    
                        // Add the recipe to $recipesNoBlacklist if it doesn't contain blacklisted ingredients
                        if (!$containsBlacklisted) {
                            array_push($recipesNoBlacklist, $indvRecipe);
                        }
                    }
                    echo json_encode($recipesNoBlacklist);
                } else {
                    echo json_encode($fullRecipes);
                } 

            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }
        } catch (PDOException $e) {
            die("Error fetching data: " . $e->getMessage());
        }
    } else {
        echo("You didn't select any ingredients!");
    } 
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

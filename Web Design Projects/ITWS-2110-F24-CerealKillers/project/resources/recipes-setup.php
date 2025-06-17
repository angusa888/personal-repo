<?php

// The Recipes Table Has Already Been Set Up During -- FUNCTIONA -- Do Not Uncomment This Code, Otherwise Duplicate Entries Will Appear In The Table
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// !!!!!!!!!!!!!!!!!! ! ! !  ! ! !  ! !!!!!!!!!!!! ! ! ! ! ! ! !!  !! !  !!!!!!!!!!!!  ! ! ! ! !  ! !!!!!!!!!!!!!!! ! ! !  ! !  ! !   !  ! !  ! ! !!

// $conn = new mysqli('localhost', 'phpmyadmin', 'Fireworks&laundry8', 'pantryDB');

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $jsonFilePath = 'recipes-final.json';

// $jsonData = file_get_contents($jsonFilePath);

// $data = json_decode($jsonData, true);

// $name = "";
// $url = "";
// $ingredients = "";

// $stmt = $conn->prepare("INSERT INTO recipes (`name`, `url`, `ingredients`) VALUES (?, ?, ?)");
// $stmt->bind_param("sss", $name, $url, $ingredients);

// foreach ($data as $recipe) {
//     $name = $recipe['name'].;
//     $url = $recipe['url'];

//     $ingredients_array = explode(", ", $recipe['ingredients']);
//     $ingredients = json_encode($ingredients_array);
//     $stmt->execute();
// }

// $stmt->close();
// $conn->close();

?>

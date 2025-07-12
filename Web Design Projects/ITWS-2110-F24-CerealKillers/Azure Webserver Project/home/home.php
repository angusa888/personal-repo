<?php
session_start();
   include "../resources/php/session_check.php";
   $nonce = base64_encode(random_bytes(16));

   header("X-Content-Type-Options: nosniff");
   header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- <link rel="stylesheet" type="text/css" href="resources/project.css"> -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
      <meta charset="UTF-8">
      <script src="./resources/js/shoppingListScript.js"></script>
      <script src="./resources/js/pantryPreview.js"></script>
      <script src="./resources/js/recipeOfDay.js"></script>
      <link rel="icon" href="../resources/Branding/logo.ico">
      <title>Garde Manger</title>
      <style>
         .content-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            /* overflow: hidden; */
            min-width: 406px;
         }
         .content-box h4 {
            margin-top: 0;
         }
         .content-box .btn {
            margin-top: auto;
         }
         .content-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            
         }
         .recipebox {
            border: solid 1px #ddd;
            position: center
         }
         .recipeofday {
            text-align: center;
            overflow: hidden;
         }
         /* Door animation styles */

         .wrapper {
            height: 300px;
            width: 250px;
            border: 5px solid var(--arctic-paradise);
            position: relative;
            perspective: 1000px;
            margin-top: 15px;
         }
         .wrappertxt {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow-y: scroll; /* Enable vertical scrolling */
            overflow-x: hidden; /* Prevent horizontal scrolling */
            scrollbar-width: none; /* Hide scrollbar in Firefox */
            -ms-overflow-style: none; /* Hide scrollbar in IE and Edge */
            width: 80%; /* Ensure it stays within the doors */
            height: 90%; /* Ensure it fits inside the door dimensions */
            box-sizing: border-box;
            text-align: center;
            padding: 5px;
         }

         .wrappertxt ul {
            list-style-type: none;
            text-align: center;
            padding: 0;
         }

         /* Hide scrollbar for WebKit browsers (Chrome, Safari, etc.) */
         .wrappertxt::-webkit-scrollbar {
            display: none;
         }


         .wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
         }
         .door {
            background-color: var(--arctic-paradise);
            height: 100%;
            width: 50%;
            position: absolute;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
         }
         #left-door {
            top: 0;
            left: 0;
            border-right: 1px solid var(--funnel-cloud);
            transform-origin: left;
            transition: transform 0.5s;
         }
         #right-door {
            top: 0;
            right: 0;
            border-left: 1px solid var(--funnel-cloud);
            transform-origin: right;
            transition: transform 0.5s;
         }
         .shape {
            border: 4px solid var(--funnel-cloud);
            width: 80%;
            height: 40%;
         }
         .knob {
            width: 10px;
            height: 30px;
            background-color: var(--ultra-pure-white);
            position: absolute;
         }
         #left-knob {
            top: 50%;
            right: 10px;
         }
         #right-knob {
            top: 50%;
            left: 10px;
         }
         .wrapper:hover #left-door {
            transform: rotateY(-140deg);
         }
         .wrapper:hover #right-door {
            transform: rotateY(140deg);
         }

            #shopping-list {
         max-height: 300px;
         overflow-y: auto;
         text-align: left;
         width: 100%;
         padding: 10px;
         }

         .list-item {
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 5px;
         border: 1px solid #ddd;
         border-radius: 6px;
         margin-bottom: 8px;
         background-color: #f8f9fa;
         }

         .list-item.checked {
         text-decoration: line-through;
         background-color: #d4edda;
         }

         .list-item input[type="checkbox"] {
         margin-right: 10px;
         }

         .list-item .editable {
         flex-grow: 1;
         outline: none;
         }



      </style>
      <link href="../resources/Branding/Branding.css" rel="stylesheet" type="text/css">
   </head>
   <body>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" nonce="<?php echo $nonce; ?>"></script>

      <!-- Navigation Bar -->
      <nav class="navbar navbar-expand-lg custom-navbar fixed-top" data-bs-theme="dark">
         <div class="container-fluid">
            <a class="navbar-brand gradient-text" href="home.php">
                  <img src="../resources/garde.svg" alt="Logo" height="40" class="d-inline-block align-text-top logo-nav">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
               <ul class="navbar-nav me-auto">
                  <li class="nav-item">
                     <a id="active-on-page" class="nav-link active" href=#>Home
                        <span class="visually-hidden">(current)</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="../virtual_pantry/virtual_pantry.php">My Pantry</a>
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
      <!-- Centered Content Boxes Section -->
      <div class="container-container">
         <div class="container content-container">
            <div class="row g-4">
               <div class="col-md-4">
                  <div class="content-box text-center">
                     <h4>Shopping List</h4>
                     <div id="shopping-list" class="mt-3"></div>
                     <button id="export-btn" class="btn btn-mustard">Export (.TXT)</button>

                  </div>
            </div>
            
               <div class="col-md-4">
                  <div class="content-box text-center">
                     <h4>Featured Recipe</h4>
      
                     <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                        <div class = "recipeofday"> 
                        <h5>Chocolate Covered S'mores</h5>
                        <p><a href="http://thepioneerwoman.com/cookin2012/08/chocolate-covered-smores/" target="_blank">View Recipe</a></p>
                        <p><strong>Ingredients:</strong> graham crackers, marshmallow creme, chocolate, nuts, sprinkles</p>
                        </div>
                     </div>
                     <a href="../add_recipe.php" class="btn btn-mustard">Submit Your Own</a>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="content-box text-center">
                     <h4>My Pantry</h4>
                     <!-- Animated Door -->
                     <div class="wrapper">
                        <div class="wrappertxt">
                           <!-- <li>asd</li> -->
                        </div>
            
                        <!-- <img src="https://source.unsplash.com/mou0S7ViElQ" alt="pantry image"> -->
                        <div id="left-door" class="door">
                           <div class="shape"></div>
                           <div class="shape"></div>
                           <div id="left-knob" class="knob"></div>
                        </div>
                        <div id="right-door" class="door">
                           <div class="shape"></div>
                           <div class="shape"></div>
                           <div id="right-knob" class="knob"></div>
                        </div>
                     </div>
                     <a href="../virtual_pantry/virtual_pantry.php" class="btn btn-mustard mt-3">Open Pantry</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </body>



</html>

   
<?php
// Database connection details
$host = 'localhost';
$dbname = 'pantryDB';
$user = 'phpmyadmin';
$pass = 'Fireworks&laundry8';

// Establish database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $recipeName = $_POST['recipeName'];
    $recipeLink = $_POST['recipeLink'];
    $ingredients = array_filter(array_map('trim', explode("\n", $_POST['ingredients'])));
    $ingredientsJson =  json_encode($ingredients, true);
    $sql = "INSERT INTO recipes (name, url, ingredients) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sss", $recipeName, $recipeLink, $ingredientsJson);

    // Execute query
    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-4 text-center'>Recipe submitted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger mt-4 text-center'>Error: " . $stmt->error . "</div>";
    }
    

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="resources/Branding/logo.ico">
    <title>Submit Your Recipe!</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Imported CSS from the provided styling */
        @import url('https://fonts.googleapis.com/css2?family=Philosopher:wght@700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400&display=swap');

        :root {
            --mustard: #FFD651;
            --funnel-cloud: #0E3661;
            --mega-blue: #366AA3;
            --arctic-paradise: #B5E2FA;
            --ultra-pure-white: #F9F7F3;

            /* fonts */
            --font-brand: 'Philosopher', sans-serif;
            --font-body: 'Albert Sans', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-image: url(./resources/form-background.jpg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            background-color: var(--ultra-pure-white);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, var(--mustard), var(--mega-blue));
        }

        .form-title {
            font-family: var(--font-brand);
            color: var(--funnel-cloud);
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--mustard);
        }

        .form-label {
            color: var(--funnel-cloud);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid var(--arctic-paradise);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--mustard);
            box-shadow: 0 0 0 0.2rem rgba(255, 214, 81, 0.25);
        }

        #submitRecipe {
            background-color: var(--mustard);
            color: var(--funnel-cloud);
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        #submitRecipe:hover {
            background-color: var(--mega-blue);
            color: var(--ultra-pure-white);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(54, 106, 163, 0.3);
        }

        .form-text {
            color: var(--mega-blue);
            font-size: 0.8rem;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

    </style>
    <link href="resources/Branding/Branding.css" rel="stylesheet" type="text/css">
</head>
<body>
<nav class="navbar navbar-expand-lg custom-navbar fixed-top" data-bs-theme="dark">
         <div class="container-fluid">
            <a class="navbar-brand gradient-text" href="./home/home.php">
                  <img src="./resources/garde.svg" alt="Logo" height="40" class="d-inline-block align-text-top logo-nav">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
               <ul class="navbar-nav me-auto">
                  <li class="nav-item">
                     <a class="nav-link" href=./home/home.php>Home
                        <span class="visually-hidden">(current)</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="./virtual_pantry/virtual_pantry.php">My Pantry</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="./account/account.php">Account</a>
                  </li>
                  <li class="nav-item">
                     <a id="active-on-page" class="nav-link active" href="#">Submit My Own Recipe</a>
                  </li>
               </ul>
            </div>
            <form id="logoutForm" action="resources/php/logout.php" method="POST">
                <button id="logout" class="btn btn-secondary my-2 my-sm-1" type="submit">Log Out</button>
            </form>
         </div>
      </nav>
    <div class="form-container">
        <h2 class="form-title">Submit Your Recipe</h2>
        <form id="recipeForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3">
                <label for="recipeName" class="form-label">Recipe Name</label>
                <input type="text" class="form-control" id="recipeName" name="recipeName" placeholder="Enter recipe name" required>
            </div>

            <div class="mb-3">
                <label for="recipeLink" class="form-label">Recipe Link</label>
                <input type="url" class="form-control" id="recipeLink" name="recipeLink" placeholder="Paste recipe URL" required>
                <small class="form-text">Share the source of your delicious recipe</small>
            </div>

            <div class="mb-3">
                <label for="ingredients" class="form-label">Ingredients</label>
                <textarea class="form-control" id="ingredients" name="ingredients" rows="5" placeholder="List each ingredient on a new line" required></textarea>
                <small class="form-text">One ingredient per line</small>
            </div>

            <div class="text-center">
                <button type="submit" class="btn" id="submitRecipe">Share My Recipe</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>
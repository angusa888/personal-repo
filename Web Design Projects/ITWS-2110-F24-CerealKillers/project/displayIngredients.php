<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = "localhost";
$dbname = "pantryDB";
$username = "phpmyadmin";
$password = "Fireworks&laundry8!";

// Connect to MySQL database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get userId from the POST request
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;

        if ($userId) {
            // Prepare SQL query to fetch the pantry data
            $sql = "SELECT * FROM users_pantry WHERE userId = :userId";
            
            // Excecute the query
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
                $stmt->execute();

                $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Return data as JSON to the client
                echo json_encode($ingredients);
            } catch (PDOException $e) {
                die("Error fetching data: " . $e->getMessage());
            }
        } else {
            echo "Improper userId.";
        }
    } else {
        echo "Invalid request method.";
    }
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
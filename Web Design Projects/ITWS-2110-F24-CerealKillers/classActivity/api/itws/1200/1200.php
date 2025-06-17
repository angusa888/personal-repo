<!-- 
 Endpoint structure: /departmentName/courseNumber 
 Required Commands: GET
 GET /ITWS/1200
 -->

 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = "localhost";
$dbname = "itws_api";
$username = "phpmyadmin";
$password = "Fireworks&laundry8"; 

// Connect to MySQL database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the request method is GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT `course`, `department`, `code`, `description` FROM `itws_data` WHERE code = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $code = "1200";
            $stmt->bindParam("s", $code);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } 
    } else {
        echo "Invalid request method. " . $e->getmessage();
    }
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
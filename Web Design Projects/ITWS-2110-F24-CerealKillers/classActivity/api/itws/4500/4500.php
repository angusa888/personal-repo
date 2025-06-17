<!-- 
 Endpoint structure: /departmentName/courseNumber 
 Required Commands: GET
 GET /ITWS/4500
 -->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "made it to 4500.php";

// Connect to MySQL database

$db = new mysqli("localhost", "phpmyadmin", "Fireworks&laundry8", "itws_api");



if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
} else {
    echo "made it past sqli request";
}

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT course, department, code, description FROM itws_data WHERE code = ?";
    try {
        $stmt = $db->prepare($sql);
        $code = "4500";
        $stmt->bind_param("s", $code);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }
} else {
    echo "Invalid request method.";
}

?>
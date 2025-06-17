<?php

session_start(); // Start the session

require_once('../../../resources/php/connection.php');

// Get the input data
$input = file_get_contents('php://input');
error_log("Raw input: " . $input);
$data = json_decode($input, true);

// Debugging: Log json_last_error message
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON Decode Error: " . json_last_error_msg());
    echo json_encode(["status" => "error", "message" => "Invalid JSON input"]);
    exit;
}

session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(["status" => "error", "message" => "User ID not found in session"]);
    exit;
}

$userId = $_SESSION['userId'];
$updatedShoppingList = json_encode($data); // Convert the input array back to JSON

// Use REPLACE INTO to fully replace the existing row with new data
$query = "
    INSERT INTO users_shopping (userId, checklist)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE checklist = VALUES(checklist)
";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $userId, $updatedShoppingList);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();

?>

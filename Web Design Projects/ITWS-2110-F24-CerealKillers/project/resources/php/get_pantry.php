<?php
require_once("./connection.php");
session_start();
$userId = $_SESSION['userId'];

$query = $conn->prepare("SELECT ingredients FROM users_pantry WHERE userId = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(json_decode($row['ingredients']));
} else {
    echo json_encode([]);
}
$query->close();
$conn->close();
?>

<?php

require_once('../../../resources/php/connection.php');
session_start();
$userId = $_SESSION['userId'];
// query users_shopping db for shopping list matching to userid from session $_SESSION['userId']
$stmt = $conn->prepare("SELECT * FROM users_shopping WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $shoppingList = json_decode($row['checklist'], true);
    echo json_encode($shoppingList);
} else {
    echo json_encode([]);
}
$stmt->close();
$conn->close();

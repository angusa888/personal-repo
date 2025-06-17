<?php
session_start();
require_once("./connection.php");

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch tags from the database
$query = "SELECT tag FROM tags";
$result = $conn->query($query);

$tags = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['tag'];
    }
}

// Return the tags as a JSON response
header('Content-Type: application/json');
echo json_encode($tags);
?>
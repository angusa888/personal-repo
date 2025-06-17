<?php
session_start();
require_once("./connection.php"); // Ensure this file contains your connection setup

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User  not logged in.']);
    exit();
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize an array to hold error messages
    $errors = [];

    // Validate required fields
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $timeOfMeetings = isset($_POST['timeOfMeetings']) ? trim($_POST['timeOfMeetings']) : '';
    $addressOne = isset($_POST['addressOne']) ? trim($_POST['addressOne']) : '';
    $addressTwo = isset($_POST['addressTwo']) ? trim($_POST['addressTwo']) : '';
    $streetAddress = trim($addressOne . (!empty($addressTwo) ? ', ' . $addressTwo : ''));
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $state = isset($_POST['state']) ? trim($_POST['state']) : '';
    $postalCode = isset($_POST['postalCode']) ? trim($_POST['postalCode']) : '';
    $country = isset($_POST['country']) ? trim($_POST['country']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'Active'; // Default to 'Active' if not set
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $tags = array_map('trim', $tags); // Trim whitespace from each tag
    $tags = array_filter($tags); // Remove empty tags

    // Check for errors
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($timeOfMeetings)) {
        $errors[] = "Time of meetings is required.";
    }
    if (empty($city)) {
        $errors[] = "City is required.";
    }
    if (empty($postalCode)) {
        $errors[] = "Postal code is required.";
    }
    if (empty($country)) {
        $errors[] = "Country is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If there are no errors, proceed with the database insertion
    if (empty($errors)) {
        // Prepare the SQL statement
        $stmt2 = $conn->prepare("INSERT INTO organizations (name, timeOfMeetings, streetAddress, city, state, postalCode, country, description, phoneNumber, email, rating, tags, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Convert tags array to JSON
        $tagsJson = json_encode($tags);

        // Bind parameters
        $stmt2->bind_param("ssssssssssisi", $name, $timeOfMeetings, $streetAddress, $city, $state, $postalCode, $country, $description, $phoneNumber, $email, $rating, $tagsJson, $status);

        // Execute the statement
        if ($stmt2->execute()) {
            echo json_encode(['success' => "New organization created successfully."]);
        } else {
            echo json_encode(['error' => "Error: " . $stmt2->error]);
        }

        $stmt2->close();
    } else {
        // Return errors as JSON
        echo json_encode(['errors' => $errors]);
    }
}

// Close the database connection
$conn->close();
?>
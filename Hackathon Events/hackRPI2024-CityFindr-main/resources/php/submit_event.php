<?php
    session_start();
    require_once("./connection.php"); // Ensure this file contains your conn connection setup
    
    if (!isset($_SESSION['userId'])) {
        header("Location: index.php"); // Redirect to login page if not logged in
        exit();
    }
    
    $userId = $_SESSION['userId'];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Initialize an array to hold error messages
    
        // Validate required fields
        $name = isset($_POST['eventName']) ? trim($_POST['eventName']) : '';
        $timeOfEvent = isset($_POST['timeOfEvent']) ? trim($_POST['timeOfEvent']) : '';
        $addressOne = isset($_POST['addressOne']) ? trim($_POST['addressOne']) : '';
        $addressTwo = isset($_POST['addressTwo']) ? trim($_POST['addressTwo']) : '';
        $city = isset($_POST['city']) ? trim($_POST['city']) : '';
        $city = isset($_POST['state']) ? trim($_POST['state']) : '';
        $city = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
        $postalCode = isset($_POST['postalCode']) ? trim($_POST['postalCode']) : '';
        $country = isset($_POST['country']) ? trim($_POST['country']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $description = isset($_POST['organization']) ? trim($_POST['organization']) : '';
        $streetAddress = trim($addressOne . (!empty($addressTwo) ? ', ' . $addressTwo : ''));
        $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
        $tags = array_map('trim', $tags); // Trim whitespace from each tag
        $tags = array_filter($tags); // Remove empty tags
    
        // Check for errors (you can add more validation as needed)
        // If there are no errors, proceed to insert the data
        if (empty($errors)) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO events (name, createdBy, organizationId, timeOfEvent, streetAddress, city, state, postalCode, country, description, phoneNumber, email, rating, tags) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
            // Set organizationId and other optional fields as needed
            $organizationId = null; // Set this variable as needed
            $rating = null; // Set this variable as needed
    
            // Convert tags array to JSON
            $tagsJson = json_encode($tags);
    
            // Bind parameters
            $stmt->bind_param("siissssissssds", $name, $userId, $organizationId, $timeOfEvent, $streetAddress, $city, $state, $postalCode, $country, $description, $phoneNumber, $email, $rating, $tagsJson);
    
            // Execute the statement
            if ($stmt->execute()) {
                echo json_encode(['success' => "New event created successfully."]);
            } else {
                echo "Error: " . $stmt->error;
            }
    
            // Close the statement
            $stmt->close();
        } else {
            // Handle errors (e.g., display them to the user)
            foreach ($errors as $error) {
                echo "<p>Error: $error</p>";
            }
        }
    }
    
    // Close the database connection
    $conn->close();
?>
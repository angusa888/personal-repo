<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./resources/cityfindr.css">
    <title>Home Page</title>
</head>
<body>
    <nav>
        <a href="./home.php">Home</a>
        <a href="./events.php">Events</a>
        <a href="./organizations.php">Organizations</a>
        <a href="./profile.php">Profile</a>
        <a href="./settings.php">Settings</a>
    </nav>

    <h1>Welcome to CityFindr!</h1>
    <p>Your personalized platform for discovering events and organizations that match your interests.</p>

    <h2>Recommended Events</h2>
    <?php
    session_start();
    require_once("./resources/php/connection.php");
    
    // Ensure userId is set in the session
    if (!isset($_SESSION['userId'])) {
        echo "<p>Please log in to see your recommendations.</p>";
        exit;
    }

    $userId = $_SESSION['userId'];

    // Fetch recommended events for the user
    $sql = "SELECT top2events FROM userprofile WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($top2events);
    $stmt->fetch();
    $stmt->close();

    // Decode the JSON array
    $eventIds = json_decode($top2events, true);

    // Fetch event details based on event IDs
    if (!empty($eventIds)) {
        foreach ($eventIds as $eventId) {
            // Fetch event details
            $eventSql = "SELECT name, timeOfEvent, streetAddress, city, state, postalCode, country, description, rating FROM events WHERE eventId = ?";
            $eventStmt = $conn->prepare($eventSql);
            $eventStmt->bind_param("i", $eventId);
            $eventStmt->execute();
            $eventStmt->bind_result($name, $timeOfEvent, $streetAddress, $city, $state, $postalCode, $country, $description, $rating);
            while ($eventStmt->fetch()) {
                echo "<div class='event-info'>";
                echo "<h3>$name</h3>";
                echo "<p>Date: $timeOfEvent</p>";
                echo "<p>Location: $streetAddress, $city, $state, $postalCode, $country</p>";
                echo "<p>Description: $description</p>";
                echo "<p>Rating: <span class='rating'>" . str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) . "</span></p>";
                echo "</div>";
            }
            $eventStmt->close();
        }
    } else {
        echo "<p>No recommended events available.</p>";
    }

    // Fetch recommended organizations for the user
    $sql = "SELECT top2organizations FROM userprofile WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($top2organizations);
    $stmt->fetch();
    $stmt->close();

    // Decode the JSON array
    $organizationIds = json_decode($top2organizations, true);

    // Fetch organization details based on organization IDs
    if (!empty($organizationIds)) {
        echo "<h2>Recommended Organizations</h2>";
        foreach ($organizationIds as $organizationId) {
            // Fetch organization details
            $orgSql = "SELECT name, phoneNumber, email, description, rating FROM organizations WHERE organizationId = ?";
            $orgStmt = $conn->prepare($orgSql);
            $orgStmt->bind_param("i", $organizationId);
            $orgStmt->execute();
            $orgStmt->bind_result($name, $phoneNumber, $email, $description, $rating);
            while ($orgStmt->fetch()) {
                echo "<div class='event-info'>";
                echo "<h3>$name</h3>";
                echo "<p>Contact: $phoneNumber, Email: $email</p>";
                echo "<p>Description: $description</p>";
                echo "<p>Rating: <span class='rating'>" . str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) . "</span></p>";
                echo "</div>";
            }
            $orgStmt->close();
        }
    } else {
        echo "<p>No recommended organizations available.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
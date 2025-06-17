<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./resources/cityfindr.css">
    <link rel="stylesheet" type="text/css" href="./resources/events.css">


    <title>Document</title>
</head>
<body>
<nav>
    <a href="./home.php">Home</a>
    <a href="./events.php">Events</a>
    <a href="./organizations.php">Organizations</a>
    <a href="./profile.php">Profile</a>
    <a href="./settings.php">Settings</a>
</nav>

<?php
session_start();

if (!isset($_SESSION['userId'])) {
    echo "User  ID not found in session.";
    exit;
}

$userId = $_SESSION['userId'];

require_once("./resources/php/connection.php");

// Fetch user preferences
$sql = "SELECT preferences FROM userProfile WHERE userId = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $preferences = json_decode ($row['preferences'], true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error decoding JSON preferences: " . json_last_error_msg();
            $tagsToMatch = [];
        } else {
            // Directly assign the preferences to tagsToMatch
            $tagsToMatch = $preferences; // Since preferences is an array
        }
    } else {
        echo "No preferences found for user ID: " . htmlspecialchars($userId);
        $tagsToMatch = [];
    }

    $result->free();
    $stmt->close();
} else {
    echo "Error preparing statement: " . htmlspecialchars($conn->error);
}

// Output the tags to match
$events = [];
$sql = "SELECT eventId, tags, name, createdBy, organizationId, timeOfEvent, streetAddress, city, state, postalCode, country, description, phoneNumber, email, rating FROM events"; // Include all necessary fields
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['tags'] = json_decode($row['tags'], true); // Decode tags JSON into an array
        $events[] = $row;
    }
} else {
    echo "No events found.";
}

// Filter events based on tagsToMatch and count matches
$matchedEvents = [];
foreach ($events as $event) {
    // Get the intersection of tags and count matches
    $matches = array_intersect($event['tags'], $tagsToMatch);
    if (!empty($matches)) {
        $event['matchCount'] = count($matches); // Count the number of matches
        $event['matchedTags'] = $matches; // Store matched tags
        $matchedEvents[] = $event; // Add the event to matchedEvents
    }
}

// Sort matched events by matchCount and then by rating
usort($matchedEvents, function($a, $b) {
    if ($a['matchCount'] === $b['matchCount']) {
        return $b['rating'] <=> $a['rating']; // Sort by rating if matchCount is the same
    }
    return $b['matchCount'] <=> $a['matchCount']; // Sort by matchCount
});

// Output matched events sorted by number of matches and rating
if (!empty($matchedEvents)) {
    foreach ($matchedEvents as $matchedEvent) {
        echo "<div class='event-info'>";
        echo "Name: " . htmlspecialchars($matchedEvent['name']) . "<br>";
        echo "Time of Event: " . htmlspecialchars($matchedEvent['timeOfEvent']) . "<br>";
        echo "Address: " . htmlspecialchars($matchedEvent['streetAddress']) . ", " . htmlspecialchars($matchedEvent['city']) . ", " . htmlspecialchars($matchedEvent['state']) . ", " . htmlspecialchars($matchedEvent['postalCode']) . ", " . htmlspecialchars($matchedEvent['country']) . "<br>";
        echo "Description: " . nl2br(htmlspecialchars($matchedEvent['description'])) . "<br>";
        echo "Phone Number: " . htmlspecialchars($matchedEvent['phoneNumber']) . "<br>";
        echo "Email: " . htmlspecialchars($matchedEvent['email']) . "<br>";
        
        $rating = (int)$matchedEvent['rating']; // Ensure rating is an integer
        echo "Rating: <span class='rating'>";
        for ($i = 0; $i < 5; $i++) {
            echo $i < $rating ? "★" : "☆"; // Print filled star for rating, empty star otherwise
        }
        echo "</span><br>";
        
        // Display matched tags as a comma-separated list
        echo "Because You Liked: " . htmlspecialchars(implode(", ", $matchedEvent['matchedTags'])) . "<br><br>";
        echo "</div>";
    }
} else {
    echo "No matching events found.";
}
?>
</body>
</html>
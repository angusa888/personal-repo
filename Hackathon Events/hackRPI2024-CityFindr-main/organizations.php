<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./resources/cityfindr.css">
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
$organizations = [];
$sql = "SELECT organizationId, tags, name, timeOfMeetings, streetAddress, city, state, postalCode, country, description, phoneNumber, email, website, rating, status FROM organizations"; // Include all necessary fields
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['tags'] = json_decode($row['tags'], true); // Decode tags JSON into an array
        $organizations[] = $row;
    }
} else {
    echo "No organizations found.";
}

// Filter organizations based on tagsToMatch and count matches
$matchedOrganizations = [];
foreach ($organizations as $organization) {
    // Get the intersection of tags and count matches
    $matches = array_intersect($organization['tags'], $tagsToMatch);
    if (!empty($matches)) {
        $organization['matchCount'] = count($matches); // Count the number of matches
        $organization['matchedTags'] = $matches; // Store matched tags
        $matchedOrganizations[] = $organization; // Add the organization to matchedOrganizations
    }
}

// Sort matched organizations by matchCount and then by rating
usort($matchedOrganizations, function($a, $b) {
    if ($a['matchCount'] === $b['matchCount']) {
        return $b['rating'] <=> $a['rating']; // Sort by rating if matchCount is the same
    }
    return $b['matchCount'] <=> $a['matchCount']; // Sort by matchCount
});

// Output matched organizations sorted by number of matches and rating
if (!empty($matchedOrganizations)) {
    foreach ($matchedOrganizations as $matchedOrganization) {
        echo "<div class='event-info'>";
        echo "Name: " . htmlspecialchars($matchedOrganization['name']) . "<br>";
        echo "Time of Meetings: " . htmlspecialchars($matchedOrganization['timeOfMeetings']) . "<br>";
        echo "Address: " . htmlspecialchars($matchedOrganization['streetAddress']) . ", " . htmlspecialchars($matchedOrganization['city']) . ", " . htmlspecialchars($matchedOrganization['state']) . ", " . htmlspecialchars($matchedOrganization['postalCode']) . ", " . htmlspecialchars($matchedOrganization['country']) . "<br>";
        echo "Description: " . nl2br(htmlspecialchars($matchedOrganization['description'])) . "<br>";
        echo "Phone Number: " . htmlspecialchars($matchedOrganization['phoneNumber']) . "<br>";
        echo "Email: " . htmlspecialchars($matchedOrganization['email']) . "<br>";
        echo "Website: " . htmlspecialchars($matchedOrganization['website']) . "<br>";
        
        $rating = (int)$matchedOrganization['rating']; // Ensure rating is an integer
        echo "Rating: <span class='rating'>";
        for ($i = 0; $i < 5; $i++) {
            echo $i < $rating ? "★" : "☆"; // Print filled star for rating, empty star otherwise
        }
        echo "</span><br>";
        
        // Display matched tags as a comma-separated list
        echo "Matched Tags: " . htmlspecialchars(implode(", ", $matchedOrganization['matchedTags'])) . "<br><br>";
        echo "</div>";
    }
} else {
    echo "No matching organizations found.";
}
?>
</body>
</html>
<?php
session_start();
require_once("./connection.php");

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Function to extract unique tags from a JSON array
function extractTags($json) {
    if ($json) {
        $tagsArray = json_decode($json, true);
        return is_array($tagsArray) ? $tagsArray : [];
    }
    return [];
}

// Fetch tags from events
$eventTags = [];
$eventResult = $conn->query("SELECT tags FROM events");
if ($eventResult) {
    while ($row = $eventResult->fetch_assoc()) {
        $eventTags = array_merge($eventTags, extractTags($row['tags']));
    }
}

// Fetch tags from organizations
$organizationTags = [];
$organizationResult = $conn->query("SELECT tags FROM organizations");
if ($organizationResult) {
    while ($row = $organizationResult->fetch_assoc()) {
        $organizationTags = array_merge($organizationTags, extractTags($row['tags']));
    }
}

// Fetch preferences from userProfile
$userPreferences = [];
$userResult = $conn->query("SELECT preferences FROM userProfile");
if ($userResult) {
    while ($row = $userResult->fetch_assoc()) {
        $userPreferences = array_merge($userPreferences, extractTags($row['preferences']));
    }
}

// Combine all tags
$allTags = array_unique(array_merge($eventTags, $organizationTags, $userPreferences));

// Insert unique tags into the all_tags table
foreach ($allTags as $tag) {
    $tag = trim($tag); // Clean up any whitespace
    if (!empty($tag)) {
        // Prepare an insert statement
        $stmt = $conn->prepare("INSERT IGNORE INTO tags (tag) VALUES (?)");
        $stmt->bind_param("s", $tag);
        $stmt->execute();
        $stmt->close();
    }
}

echo "Tags populated successfully.";
?>
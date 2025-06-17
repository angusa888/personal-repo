<?php
require_once('../resources/php/connection.php');

$userId = $_SESSION['userId'];

if ($userId) {
    $sql = "SELECT blacklist FROM users_pantry WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $blacklist = $result->fetch_assoc();
        if (isset($blacklist)) {
            $ingredients = json_decode($blacklist['blacklist'], true); 
            if ($ingredients != null && !empty($ingredients)) {
                echo '<div class="scrollable-card">';
                echo '<ul>';
                
                foreach ($ingredients as $ingredient) {
                    // Use htmlspecialchars to prevent XSS
                    echo '<li>' . htmlspecialchars($ingredient) . 
                         '<span class="close-mark" style="cursor:pointer; color:var(--mega-blue); font-weight:bold; margin-left:5px;" onclick="removeItem(\'' . addslashes(htmlspecialchars($ingredient)) . '\')">&times;</span></li>'; 
                }
                
                echo '</ul>';
                echo '</div>';
            } else {
                // Optional: Display a message when there are no ingredients
                echo '<div class="card-body">';
                echo '<p>No blacklist items!</p>'; // You can customize this message
                echo '</div>';
            }
        }
    } else {
        echo '<p>Error fetching data: ' . htmlspecialchars($stmt->error) . '</p>';
    }
} else {
    echo '<p>Improper userId.</p>';
}
?>
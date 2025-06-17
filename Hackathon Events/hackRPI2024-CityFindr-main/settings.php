<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./resources/cityfindr.css">
    <link rel="stylesheet" type="text/css" href="./resources/settings.css">
    <title>Change Personal Information</title> <!-- Updated title for clarity -->
</head>
<body>
    <nav>
        <a href="./home.php">Home</a>
        <a href="./events.php">Events</a>
        <a href="./organizations.php">Organizations</a>
        <a href="./profile.php">Profile</a>
        <a href="./settings.php">Settings</a>
    </nav>

    <h2>Change Personal Information</h2>

    <h3>Change Password</h3>
    <form id="changePasswordForm" action="./resources/php/change_password.php" method="POST">
        <input type="password" name="oldPassword" placeholder="Enter Old Password" required> <!-- Use type="password" -->
        <input type="password" name="newPassword" placeholder="Enter New Password" required> <!-- Use type="password" -->
        <input type="submit" value="Submit"> <!-- Use value for submit button -->
    </form>

    <h3>Change Location</h3>
    <form action="./resources/php/change_location.php" method="POST"> <!-- Added action and method -->
        <input type="text" name="addressOne" placeholder="Enter new address one" required> <!-- Use type="text" and name attributes -->
        <input type="text" name="addressTwo" placeholder="Enter New address two if possible">
        <input type="text" name="state" placeholder="Enter your state (if applicable)">
        <input type="text" name="city" placeholder="Enter your city" required>
        <input type="text" name="postalCode" placeholder="Enter your postal code" required>
        <input type="text" name="country" placeholder="Enter your country" required>
        <input type="submit" value="Submit"> <!-- Use value for submit button -->
    </form>

    <h3>Delete Account</h3>
    <form action="./resources/php/delete_account.php" method="POST"> <!-- Added action and method -->
        <input type="text" name="username" placeholder="Enter username" required> <!-- Use type="text" and name attributes -->
        <input type="password" name="password" placeholder="Enter password" required> <!-- Use type="password" -->
        <input type="submit" value="Submit"> <!-- Use value for submit button -->
    </form>

    <script src="./resources/settings/settings.js"></script>
</body>
</html>
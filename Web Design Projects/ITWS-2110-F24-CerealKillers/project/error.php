<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: black;
        }
        a {
            color: DodgerBlue;
        }
    </style>
</head>
<body class="d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">

    <div class="text-center"> <!-- Corrected this line -->
        <?php
        error_log("Error.php accessed with error code: " . $errorCode);

        $errorCode = isset($_GET['error']) ? intval($_GET['error']) : 404;

        // Define messages for different error codes
        $messages = [
            404 => "Oops! The page you're looking for doesn't exist.",
            500 => "Oops! Something went wrong on our end.",
            403 => "Oops! You don't have permission to access this page.",
            // Add more error codes and messages as needed
        ];

        // Set the message based on the error code
        $message = isset($messages[$errorCode]) ? $messages[$errorCode] : "An unknown error occurred.";
        ?>

        <h1 class="display-1"><?php echo $errorCode; ?></h1>
        <p class="lead"><?php echo $message; ?></p>
        <p>You can go back to the <a href="./home.php">homepage</a> or try searching for what you need.</p>
    </div>
</body>
</html>
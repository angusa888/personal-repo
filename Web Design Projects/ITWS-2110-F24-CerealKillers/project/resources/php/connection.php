<?php
    $servername = 'localhost';
    $username = 'localuser';
    $password ='nozsuj-8cevmy-nagviM';
    $dbname = 'pantryDB';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(''. $conn->connect_error);
    }
?>
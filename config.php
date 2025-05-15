<?php
    // Database Attributes
    $host_name = "localhost";
    $user_name = "root";
    $database_name = "user";
    $database_password = "";

    try {
        // Connecting to Database using PDO
        $connection = new PDO("mysql:host=$host_name;dbname=$database_name", $user_name, $database_password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

?>

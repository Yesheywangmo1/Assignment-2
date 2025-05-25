<?php
// db_connect.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'campus_club_bhutan');
define('DB_USER', 'root'); // Replace with your database username
define('DB_PASS', '');     // Replace with your database password (e.g., 'root' or '' for XAMPP)

// Set up a secure PDO connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    // Set PDO error mode to exception for better error tracking
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log error for debugging (in production, log to a file)
    error_log("Database Connection Error: " . $e->getMessage());
    die("Connection to database failed. Please contact system administrator.");
}
?>

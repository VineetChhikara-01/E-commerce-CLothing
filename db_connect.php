<?php
// Database Configuration
$host = 'localhost';      // Hostinger usually uses 'localhost'
$username = 'root';       // REPLACE with your Database Username
$password = 'password';           // REPLACE with your Database Password
$dbname = 'clothes'; // REPLACE with your Database Name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>

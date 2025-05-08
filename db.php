<?php
// Database configuration
$host = "localhost";  // Your database host (localhost for local development)
$user = "root";       // Your MySQL username (default is 'root' for XAMPP/WAMP)
$pass = "";           // Your MySQL password (default is '' for XAMPP/WAMP)
$dbname = "adminmanage";  // The name of the database you created

// Create a connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

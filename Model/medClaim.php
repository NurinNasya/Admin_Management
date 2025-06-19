<?php
$host = "localhost";
$user = "root"; // adjust if you set a password
$pass = "";     // adjust if you set a password
$db = "adminmanage"; // replace with your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

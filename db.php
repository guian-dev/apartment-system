<?php
$servername = "localhost";
$username = "root"; // default XAMPP user
$password = ""; // leave empty if no password
$database = "kagayan_db"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
// echo "✅ Connected successfully";
?>

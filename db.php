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

// Set charset to utf8
$conn->set_charset("utf8");

// Function to execute SQL queries safely
if (!function_exists('executeQuery')) {
    function executeQuery($sql, $params = []) {
        global $conn;
        
        if (empty($params)) {
            $result = $conn->query($sql);
            if (!$result) {
                die("Query failed: " . $conn->error);
            }
            return $result;
        } else {
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }
    }
}

// Function to sanitize input
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        global $conn;
        return $conn->real_escape_string(trim($data));
    }
}

// Function to format currency
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return '₱' . number_format($amount, 2);
    }
}

// Function to format date
if (!function_exists('formatDate')) {
    function formatDate($date) {
        return date('M j, Y', strtotime($date));
    }
}
?>

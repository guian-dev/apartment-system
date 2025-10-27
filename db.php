<?php
// Database connection settings
$host = 'localhost';           // Usually localhost
$username = 'root';            // Your MySQL username
$password = '';                // Your MySQL password (empty for XAMPP)
$database = 'kagayan_db'; // Your database name

// Flag to enable/disable database connection requirement
$require_db_connection = false; // Set to false to allow the site to work without database

// Create connection with error handling
try {
    if ($require_db_connection) {
        $conn = new mysqli($servername, $username, $password, $database, $port);
        
        // Check connection
        if ($conn->connect_error) {
            die("❌ Connection failed: " . $conn->connect_error);
        }
    } else {
        // Try to connect, but don't die if it fails
        $conn = @new mysqli($servername, $username, $password, $database, $port);
        
        // If connection fails, create a dummy connection object
        if ($conn->connect_error) {
            // Define a dummy connection class for fallback
            class DummyConnection {
                public function query($sql) {
                    return new DummyResult();
                }
                
                public function set_charset($charset) {
                    return true;
                }
                
                public function prepare($sql) {
                    return new DummyStatement();
                }
                
                public function real_escape_string($str) {
                    return $str;
                }
            }
            
            class DummyResult {
                public $num_rows = 0;
                
                public function fetch_assoc() {
                    return null;
                }
                
                public function fetch_all(int $mode = MYSQLI_NUM) {
                    return [];
                }
            }
            
            class DummyStatement {
                public function bind_param($types, ...$params) {
                    return true;
                }
                
                public function execute() {
                    return true;
                }
                
                public function get_result() {
                    return new DummyResult();
                }
            }
            
            $conn = new DummyConnection();
        }
    }
} catch (Exception $e) {
    if ($require_db_connection) {
        die("❌ Connection failed: Make sure XAMPP MySQL service is running. Error: " . $e->getMessage());
    } else {
        // Create a dummy connection
        class DummyConnection {
            public function query($sql) {
                return new DummyResult();
            }
            
            public function set_charset($charset) {
                return true;
            }
            
            public function prepare($sql) {
                return new DummyStatement();
            }
            
            public function real_escape_string($str) {
                return $str;
            }
        }
        
        class DummyResult {
            public $num_rows = 0;
            
            public function fetch_assoc() {
                return null;
            }
            
            public function fetch_all(int $mode = MYSQLI_NUM) {
                return [];
            }
        }
        
        class DummyStatement {
            public function bind_param($types, ...$params) {
                return true;
            }
            
            public function execute() {
                return true;
            }
            
            public function get_result() {
                return new DummyResult();
            }
        }
        
        $conn = new DummyConnection();
    }
}

// Set charset to utf8
$conn->set_charset("utf8");

// Function to execute SQL queries safely
function executeQuery($sql, $params = []) {
    global $conn;
    
    // Check if we're using a dummy connection
    if ($conn instanceof DummyConnection) {
        // Return dummy results for different query types
        if (stripos($sql, 'SELECT') === 0) {
            return new DummyResult();
        } else {
            return true;
        }
    }
    
    if (empty($params)) {
        $result = $conn->query($sql);
        if (!$result) {
            // Return empty result instead of dying
            return new DummyResult();
        }
        return $result;
    } else {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Return empty result instead of dying
            return new DummyResult();
        }
        
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            // Return empty result instead of dying
            return new DummyResult();
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
}

// Function to sanitize input
function sanitizeInput($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

// Function to format currency
function formatCurrency($amount) {
    return '₱' . number_format($amount, 2);
}

// Function to format date
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}
?>

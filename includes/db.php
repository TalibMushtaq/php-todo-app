<?php
/**
 * Database Connection Handler
 * 
 * Handles database connection and provides helper functions
 */

require_once __DIR__ . '/config.php';

// Create connection with error handling
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to prevent SQL injection
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    error_log($e->getMessage());
    
    if (DEBUG_MODE) {
        die(json_encode([
            'success' => false,
            'message' => 'Database connection error: ' . $e->getMessage()
        ]));
    } else {
        die(json_encode([
            'success' => false,
            'message' => 'Database connection error'
        ]));
    }
}

/**
 * Sanitize output to prevent XSS attacks
 * 
 * @param string $string The string to sanitize
 * @return string Sanitized string
 */
function sanitize_output($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Send JSON response
 * 
 * @param array $data Response data
 * @param int $status_code HTTP status code
 */
function send_json($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


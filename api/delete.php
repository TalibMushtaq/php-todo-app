<?php
/**
 * Delete Todo API Endpoint
 * 
 * Handles POST requests to delete todos
 */

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_json(['success' => false, 'message' => 'Method not allowed'], 405);
}

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

if ($id <= 0) {
    send_json(['success' => false, 'message' => 'Invalid task ID'], 400);
}

try {
    $stmt = $conn->prepare("DELETE FROM todos WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json(['success' => true, 'message' => 'Task deleted']);
        } else {
            send_json(['success' => false, 'message' => 'Task not found'], 404);
        }
    } else {
        throw new Exception("Failed to delete task: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log($e->getMessage());
    send_json(['success' => false, 'message' => 'Failed to delete task'], 500);
}

$conn->close();


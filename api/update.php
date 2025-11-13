<?php
/**
 * Update Todo API Endpoint
 * 
 * Handles POST requests to update todos (status, task text, or priority)
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
    // Check if we're updating done status or task content
    if (isset($_POST["done"])) {
        // Toggle done status
        $done = intval($_POST["done"]);
        $done = $done ? 1 : 0; // Ensure boolean
        $stmt = $conn->prepare("UPDATE todos SET is_done=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("ii", $done, $id);
        
    } elseif (isset($_POST["task"])) {
        // Update task text
        $task = trim($_POST["task"]);
        if (empty($task)) {
            send_json(['success' => false, 'message' => 'Task cannot be empty'], 400);
        }
        if (strlen($task) > 500) {
            send_json(['success' => false, 'message' => 'Task is too long'], 400);
        }
        
        $stmt = $conn->prepare("UPDATE todos SET task=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("si", $task, $id);
        
    } elseif (isset($_POST["priority"])) {
        // Update priority
        $priority = max(0, min(2, intval($_POST["priority"])));
        $stmt = $conn->prepare("UPDATE todos SET priority=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("ii", $priority, $id);
        
    } else {
        send_json(['success' => false, 'message' => 'No update data provided'], 400);
    }
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            send_json(['success' => true, 'message' => 'Task updated']);
        } else {
            send_json(['success' => false, 'message' => 'Task not found or no changes made'], 404);
        }
    } else {
        throw new Exception("Update failed: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log($e->getMessage());
    send_json(['success' => false, 'message' => 'Failed to update task'], 500);
}

$conn->close();


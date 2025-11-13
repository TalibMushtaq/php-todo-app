<?php
/**
 * Add Todo API Endpoint
 * 
 * Handles POST requests to add new todos
 */

require_once __DIR__ . '/../includes/db.php';

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_json(['success' => false, 'message' => 'Method not allowed'], 405);
}

// Get and validate input
$task = trim($_POST["task"] ?? "");

if (empty($task)) {
    send_json(['success' => false, 'message' => 'Task cannot be empty'], 400);
}

if (strlen($task) > 500) {
    send_json(['success' => false, 'message' => 'Task is too long (max 500 characters)'], 400);
}

// Get optional priority
$priority = isset($_POST["priority"]) ? intval($_POST["priority"]) : 1;
$priority = max(0, min(2, $priority)); // Clamp between 0-2

// Insert task
try {
    $stmt = $conn->prepare("INSERT INTO todos (task, priority, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $task, $priority);
    
    if ($stmt->execute()) {
        send_json([
            'success' => true,
            'id' => $stmt->insert_id,
            'task' => $task,
            'priority' => $priority,
            'is_done' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } else {
        throw new Exception("Failed to insert task");
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    send_json(['success' => false, 'message' => 'Failed to add task'], 500);
}

$conn->close();


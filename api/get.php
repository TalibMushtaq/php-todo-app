<?php
/**
 * Get Todos API Endpoint
 * 
 * Handles GET requests to retrieve todos with filtering and sorting
 */

require_once __DIR__ . '/../includes/db.php';

// Get filter parameters
$filter = $_GET['filter'] ?? 'all'; // all, active, completed
$sort = $_GET['sort'] ?? 'created'; // created, priority, task

// Validate filter
if (!in_array($filter, ['all', 'active', 'completed'])) {
    $filter = 'all';
}

// Validate sort
if (!in_array($sort, ['created', 'priority', 'task'])) {
    $sort = 'created';
}

// Build query based on filter
$where_clause = "";
if ($filter === 'active') {
    $where_clause = "WHERE is_done = 0";
} elseif ($filter === 'completed') {
    $where_clause = "WHERE is_done = 1";
}

// Build ORDER BY clause
$order_clause = "ORDER BY ";
switch ($sort) {
    case 'priority':
        $order_clause .= "priority DESC, created_at DESC";
        break;
    case 'task':
        $order_clause .= "task ASC";
        break;
    default:
        $order_clause .= "created_at DESC";
}

try {
    $query = "SELECT id, task, is_done, priority, created_at, updated_at 
              FROM todos $where_clause $order_clause";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $todos = [];
    
    while ($row = $result->fetch_assoc()) {
        $todos[] = [
            'id' => (int)$row['id'],
            'task' => $row['task'],
            'is_done' => (int)$row['is_done'],
            'priority' => (int)$row['priority'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at']
        ];
    }
    
    send_json([
        'success' => true,
        'todos' => $todos,
        'count' => count($todos)
    ]);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    send_json(['success' => false, 'message' => 'Failed to fetch tasks'], 500);
}

$conn->close();


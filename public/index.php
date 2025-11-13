<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List - Modern & Productive</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>✓ My Tasks</h1>
            <div class="stats">
                <span id="totalTasks">0</span> tasks · 
                <span id="completedTasks">0</span> completed
            </div>
        </header>

        <form id="todoForm">
            <div class="input-group">
                <input type="text" 
                       name="task" 
                       id="taskInput"
                       placeholder="What needs to be done?" 
                       maxlength="500"
                       required>
                
                <select name="priority" id="prioritySelect">
                    <option value="0">Low</option>
                    <option value="1" selected>Normal</option>
                    <option value="2">High</option>
                </select>
                
                <button type="submit" class="btn-add">
                    <span class="btn-text">Add Task</span>
                    <span class="btn-icon">+</span>
                </button>
            </div>
        </form>

        <div class="filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="active">Active</button>
            <button class="filter-btn" data-filter="completed">Completed</button>
            
            <div class="sort-group">
                <label>Sort by:</label>
                <select id="sortSelect">
                    <option value="created">Date Added</option>
                    <option value="priority">Priority</option>
                    <option value="task">Name</option>
                </select>
            </div>
        </div>

        <div id="emptyState" class="empty-state">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M9 11l3 3L22 4"></path>
                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
            </svg>
            <p>No tasks yet. Add one to get started!</p>
        </div>

        <ul id="todoList" class="todo-list"></ul>
    </div>

    <div id="toast" class="toast"></div>

    <script src="../assets/js/script.js"></script>
</body>
</html>


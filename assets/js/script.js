/**
 * Todo App JavaScript
 * 
 * Handles all client-side functionality for the todo application
 */

// State management
let currentFilter = "all";
let currentSort = "created";

// DOM elements
const form = document.getElementById("todoForm");
const list = document.getElementById("todoList");
const emptyState = document.getElementById("emptyState");
const filterBtns = document.querySelectorAll(".filter-btn");
const sortSelect = document.getElementById("sortSelect");
const taskInput = document.getElementById("taskInput");
const prioritySelect = document.getElementById("prioritySelect");

// Initialize app
document.addEventListener("DOMContentLoaded", () => {
    loadTodos();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Form submission
    form.addEventListener("submit", handleAddTask);

    // Filter buttons
    filterBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            currentFilter = btn.dataset.filter;
            filterBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            loadTodos();
        });
    });

    // Sort selector
    sortSelect.addEventListener("change", (e) => {
        currentSort = e.target.value;
        loadTodos();
    });
}

// Load todos from server
async function loadTodos() {
    try {
        const response = await fetch(
            `../api/get.php?filter=${currentFilter}&sort=${currentSort}`
        );
        const data = await response.json();

        if (data.success) {
            renderTodos(data.todos);
            updateStats(data.todos);
        } else {
            showToast("Failed to load tasks", "error");
        }
    } catch (error) {
        console.error("Error loading todos:", error);
        showToast("Network error", "error");
    }
}

// Render todos to DOM
function renderTodos(todos) {
    list.innerHTML = "";

    if (todos.length === 0) {
        emptyState.classList.add("show");
        list.style.display = "none";
        return;
    }

    emptyState.classList.remove("show");
    list.style.display = "flex";

    todos.forEach((todo) => {
        const li = createTodoElement(todo);
        list.appendChild(li);
    });
}

// Create todo element
function createTodoElement(todo) {
    const li = document.createElement("li");
    li.className = `todo-item priority-${todo.priority}${
        todo.is_done ? " done" : ""
    }`;
    li.dataset.id = todo.id;

    const priorityLabels = ["low", "normal", "high"];
    const priorityLabel = priorityLabels[todo.priority];

    const date = new Date(todo.created_at);
    const formattedDate = date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
    });

    li.innerHTML = `
        <div class="checkbox" onclick="toggleTask(${todo.id}, ${
        todo.is_done
    })"></div>
        <div class="task-content">
            <div class="task-text">${escapeHtml(todo.task)}</div>
            <div class="task-meta">
                <span class="priority-badge ${priorityLabel}">${priorityLabel}</span>
                <span>${formattedDate}</span>
            </div>
        </div>
        <div class="task-actions">
            <button class="btn-icon delete" onclick="deleteTask(${
                todo.id
            })" title="Delete">
                🗑️
            </button>
        </div>
    `;

    return li;
}

// Handle form submission
async function handleAddTask(e) {
    e.preventDefault();

    const task = taskInput.value.trim();
    const priority = prioritySelect.value;

    if (!task) {
        showToast("Please enter a task", "error");
        return;
    }

    try {
        const response = await fetch("../api/add.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `task=${encodeURIComponent(task)}&priority=${priority}`,
        });

        const data = await response.json();

        if (data.success) {
            form.reset();
            prioritySelect.value = "1"; // Reset to normal priority
            loadTodos();
            showToast("Task added successfully", "success");
            taskInput.focus();
        } else {
            showToast(data.message || "Failed to add task", "error");
        }
    } catch (error) {
        console.error("Error adding task:", error);
        showToast("Network error", "error");
    }
}

// Toggle task completion
async function toggleTask(id, currentStatus) {
    const newStatus = currentStatus ? 0 : 1;

    try {
        const response = await fetch("../api/update.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${id}&done=${newStatus}`,
        });

        const data = await response.json();

        if (data.success) {
            loadTodos();
            showToast(newStatus ? "Task completed! 🎉" : "Task reopened", "success");
        } else {
            showToast("Failed to update task", "error");
        }
    } catch (error) {
        console.error("Error toggling task:", error);
        showToast("Network error", "error");
    }
}

// Delete task
async function deleteTask(id) {
    if (!confirm("Are you sure you want to delete this task?")) {
        return;
    }

    try {
        const response = await fetch("../api/delete.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${id}`,
        });

        const data = await response.json();

        if (data.success) {
            loadTodos();
            showToast("Task deleted", "success");
        } else {
            showToast("Failed to delete task", "error");
        }
    } catch (error) {
        console.error("Error deleting task:", error);
        showToast("Network error", "error");
    }
}

// Update statistics
function updateStats(todos) {
    const total = todos.length;
    const completed = todos.filter((t) => t.is_done === 1).length;

    document.getElementById("totalTasks").textContent = total;
    document.getElementById("completedTasks").textContent = completed;
}

// Show toast notification
function showToast(message, type = "info") {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.className = `toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

// Keyboard shortcuts
document.addEventListener("keydown", (e) => {
    // Focus input with Ctrl/Cmd + K
    if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        taskInput.focus();
    }
});


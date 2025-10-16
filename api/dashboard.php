<?php
$pageTitle = "Dashboard";
require_once 'includes/header.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setMessage("Please login to access the dashboard.", 'error');
    redirect('login.php');
}

// Initialize tasks file if it doesn't exist
$tasksFile = __DIR__ . '/data/tasks.json';
if (!file_exists($tasksFile)) {
    file_put_contents($tasksFile, json_encode([]));
}

// Get user's tasks
function getUserTasks($username)
{
    $tasksFile = __DIR__ . '/data/tasks.json';
    $allTasks = json_decode(file_get_contents($tasksFile), true) ?: [];
    return array_filter($allTasks, function ($task) use ($username) {
        return $task['username'] === $username;
    });
}

// Handle task actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $taskId = $_POST['task_id'] ?? '';

    $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];

    switch ($action) {
        case 'add':
            $title = sanitizeInput($_POST['title']);
            $description = sanitizeInput($_POST['description'] ?? '');

            if (!empty($title)) {
                $newTask = [
                    'id' => uniqid(),
                    'username' => $_SESSION['user']['username'],
                    'title' => $title,
                    'description' => $description,
                    'status' => 'pending',
                    'priority' => $_POST['priority'] ?? 'medium',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $tasks[] = $newTask;
                file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
                setMessage("Task added successfully!", 'success');
            }
            break;

        case 'toggle':
            foreach ($tasks as &$task) {
                if ($task['id'] === $taskId) {
                    $task['status'] = $task['status'] === 'completed' ? 'pending' : 'completed';
                    $task['updated_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
            break;

        case 'delete':
            $tasks = array_filter($tasks, function ($task) use ($taskId) {
                return $task['id'] !== $taskId;
            });
            file_put_contents($tasksFile, json_encode(array_values($tasks), JSON_PRETTY_PRINT));
            setMessage("Task deleted successfully!", 'success');
            break;
    }

    redirect('dashboard.php');
}

$userTasks = getUserTasks($_SESSION['user']['username']);
$pendingTasks = array_filter($userTasks, function ($task) {
    return $task['status'] === 'pending';
});
$completedTasks = array_filter($userTasks, function ($task) {
    return $task['status'] === 'completed';
});
?>

<div class="dashboard-header">
    <h1>Welcome, <?php echo $_SESSION['user']['name']; ?>!</h1>
    <p>Manage your tasks and stay organized</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-number"><?php echo count($userTasks); ?></div>
        <div class="stat-label">Total Tasks</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo count($pendingTasks); ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo count($completedTasks); ?></div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">
            <?php echo count($userTasks) > 0 ? round((count($completedTasks) / count($userTasks)) * 100) : 0; ?>%
        </div>
        <div class="stat-label">Completion Rate</div>
    </div>
</div>

<div class="dashboard-content">
    <div class="task-form-section">
        <div class="card">
            <h2>Add New Task</h2>
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="action" value="add">

                <div class="form-group">
                    <label for="title">Task Title *</label>
                    <input type="text" id="title" name="title" class="form-control" required
                        placeholder="What needs to be done?">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"
                        placeholder="Additional details..."></textarea>
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
        </div>
    </div>

    <div class="tasks-section">
        <!-- Pending Tasks -->
        <div class="card">
            <h2>Pending Tasks (<?php echo count($pendingTasks); ?>)</h2>

            <?php if (empty($pendingTasks)): ?>
                <p class="no-tasks">No pending tasks. Add a new task above!</p>
            <?php else: ?>
                <div class="tasks-list">
                    <?php foreach ($pendingTasks as $task): ?>
                        <div class="task-item task-priority-<?php echo $task['priority']; ?>">
                            <div class="task-content">
                                <h4 class="task-title"><?php echo $task['title']; ?></h4>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-description"><?php echo $task['description']; ?></p>
                                <?php endif; ?>
                                <div class="task-meta">
                                    <span class="task-priority"><?php echo ucfirst($task['priority']); ?> priority</span>
                                    <span class="task-date">Created: <?php echo $task['created_at']; ?></span>
                                </div>
                            </div>
                            <div class="task-actions">
                                <form method="POST" action="dashboard.php" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-small">Complete</button>
                                </form>
                                <form method="POST" action="dashboard.php" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-small"
                                        onclick="return confirm('Are you sure you want to delete this task?');">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Completed Tasks -->
        <div class="card">
            <h2>Completed Tasks (<?php echo count($completedTasks); ?>)</h2>

            <?php if (empty($completedTasks)): ?>
                <p class="no-tasks">No completed tasks yet.</p>
            <?php else: ?>
                <div class="tasks-list">
                    <?php foreach ($completedTasks as $task): ?>
                        <div class="task-item task-completed">
                            <div class="task-content">
                                <h4 class="task-title"><s><?php echo $task['title']; ?></s></h4>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-description"><?php echo $task['description']; ?></p>
                                <?php endif; ?>
                                <div class="task-meta">
                                    <span class="task-date">Completed: <?php echo $task['updated_at']; ?></span>
                                </div>
                            </div>
                            <div class="task-actions">
                                <form method="POST" action="dashboard.php" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-warning btn-small">Reopen</button>
                                </form>
                                <form method="POST" action="dashboard.php" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-small"
                                        onclick="return confirm('Are you sure you want to delete this task?');">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
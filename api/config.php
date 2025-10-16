<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// App configuration
define('APP_NAME', 'TaskMaster');
define('APP_VERSION', '1.0.0');
define('BASE_URL', getBaseUrl());

// Database simulation files
define('USERS_FILE', __DIR__ . '/data/users.json');
define('TASKS_FILE', __DIR__ . '/data/tasks.json');

// Initialize data files
function initializeDataFiles()
{
    $dataDir = __DIR__ . '/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }

    // Initialize users file
    if (!file_exists(USERS_FILE)) {
        $initialUsers = [
            'admin' => [
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'email' => 'admin@taskmaster.com',
                'name' => 'Administrator',
                'role' => 'admin',
                'registered_at' => date('Y-m-d H:i:s')
            ]
        ];
        file_put_contents(USERS_FILE, json_encode($initialUsers, JSON_PRETTY_PRINT));
    }

    // Initialize tasks file
    if (!file_exists(TASKS_FILE)) {
        file_put_contents(TASKS_FILE, json_encode([]));
    }
}

// Get base URL dynamically
function getBaseUrl()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocol . '://' . $host;
}

// Helper function to get users
function getUsers()
{
    return json_decode(file_get_contents(USERS_FILE), true) ?: [];
}

// Save users
function saveUsers($users)
{
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// Initialize data files on include
initializeDataFiles();

<?php
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function loginUser($username, $password) {
    $users = getUsers();
    
    if (!isset($users[$username])) {
        return false;
    }
    
    $user = $users[$username];
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'username' => $username,
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }
    
    return false;
}

function logoutUser() {
    session_destroy();
    session_start();
}

function registerUser($username, $password, $email, $name) {
    $users = getUsers();
    
    if (isset($users[$username])) {
        return false; // User already exists
    }
    
    $users[$username] = [
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'email' => $email,
        'name' => $name,
        'role' => 'user'
    ];
    
    saveUsers($users);
    return true;
}
?>
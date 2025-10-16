<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$username = $_SESSION['user']['username'] ?? 'Guest';
logoutUser();

setMessage("Goodbye, $username! You have been logged out successfully.", 'success');
redirect('index.php');
?>
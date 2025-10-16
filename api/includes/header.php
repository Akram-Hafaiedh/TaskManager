<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $pageTitle ?? 'Home'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2><?php echo APP_NAME; ?></h2>
            </div>
            <div class="nav-menu">
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">About</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                    <a href="logout.php" class="nav-link">Logout (<?php echo $_SESSION['user']['username']; ?>)</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?>">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>
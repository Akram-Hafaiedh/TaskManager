<?php
$pageTitle = "Home";
require_once 'includes/header.php';
?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Welcome to TaskMaster</h1>
        <p>Your simple, powerful task management solution built with PHP</p>
        <?php if (!isLoggedIn()): ?>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary btn-large">Get Started</a>
                <a href="login.php" class="btn btn-outline btn-large">Login</a>
            </div>
        <?php else: ?>
            <div class="hero-buttons">
                <a href="dashboard.php" class="btn btn-primary btn-large">Go to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="features-grid">
    <div class="feature-card">
        <div class="feature-icon">📝</div>
        <h3>Create Tasks</h3>
        <p>Easily create and organize your daily tasks</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">✅</div>
        <h3>Track Progress</h3>
        <p>Mark tasks as complete and track your productivity</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">📊</div>
        <h3>View Analytics</h3>
        <p>See your task completion statistics and trends</p>
    </div>
</div>

<div class="stats-section">
    <div class="container">
        <h2>Why Choose TaskMaster?</h2>
        <div class="stats-grid">
            <div class="stat">
                <div class="stat-number">100%</div>
                <div class="stat-label">Free</div>
            </div>
            <div class="stat">
                <div class="stat-number">PHP</div>
                <div class="stat-label">Powered</div>
            </div>
            <div class="stat">
                <div class="stat-number">Vercel</div>
                <div class="stat-label">Hosted</div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
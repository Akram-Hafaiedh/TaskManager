<?php
$pageTitle = "About";
require_once 'includes/header.php';
?>

<div class="card">
    <h1>About TaskMaster</h1>

    <div class="about-content">
        <div class="about-text">
            <p>TaskMaster is a classic PHP application demonstrating how to build a complete web application using traditional PHP patterns while running on modern serverless infrastructure.</p>

            <h2>Features</h2>
            <ul>
                <li>User registration and authentication</li>
                <li>Task creation and management</li>
                <li>Profile management</li>
                <li>Responsive design</li>
                <li>Session management</li>
                <li>Form validation</li>
            </ul>

            <h2>Technology Stack</h2>
            <div class="tech-stack">
                <div class="tech-item">
                    <strong>Backend:</strong> PHP 8.x
                </div>
                <div class="tech-item">
                    <strong>Frontend:</strong> HTML5, CSS3, JavaScript
                </div>
                <div class="tech-item">
                    <strong>Storage:</strong> JSON files (simulated database)
                </div>
                <div class="tech-item">
                    <strong>Hosting:</strong> Vercel Serverless Functions
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Development Approach</h2>
    <p>This project follows classic PHP development patterns:</p>

    <div class="approach-grid">
        <div class="approach-item">
            <h3>🧱 Procedural PHP</h3>
            <p>Traditional PHP scripting with includes and functions</p>
        </div>
        <div class="approach-item">
            <h3>🔐 Session-Based Auth</h3>
            <p>Classic session management for user authentication</p>
        </div>
        <div class="approach-item">
            <h3>📁 File-Based Data</h3>
            <p>JSON files simulating database tables</p>
        </div>
        <div class="approach-item">
            <h3>🎨 Classic Styling</h3>
            <p>Traditional CSS with modern responsive design</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
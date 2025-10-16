<?php
$pageTitle = "Login";
require_once 'includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        setMessage("Welcome back, " . $_SESSION['user']['name'] . "!", 'success');
        redirect('dashboard.php');
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Login to Your Account</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo $_POST['username'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>

        <div class="demo-accounts">
            <h3>Demo Accounts</h3>
            <div class="demo-account">
                <strong>Admin:</strong> admin / admin123
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
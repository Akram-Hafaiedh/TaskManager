<?php
$pageTitle = "Register";
require_once 'includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = sanitizeInput($_POST['email']);
    $full_name = sanitizeInput($_POST['full_name']);

    // Validation
    $errors = [];

    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Check if username exists
    $users = getUsers();
    if (isset($users[$username])) {
        $errors[] = "Username already exists.";
    }

    if (empty($errors)) {
        if (registerUser($username, $password, $email, $full_name)) {
            setMessage("Registration successful! Please login.", 'success');
            redirect('login.php');
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Create Your Account</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control"
                    value="<?php echo $_POST['full_name'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo $_POST['email'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo $_POST['username'] ?? ''; ?>" required>
                <small>Minimum 3 characters</small>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <small>Minimum 6 characters</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
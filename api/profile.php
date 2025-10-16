<?php
$pageTitle = "Profile";
require_once 'includes/header.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setMessage("Please login to view your profile.", 'error');
    redirect('login.php');
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $email = sanitizeInput($_POST['email']);
    $name = sanitizeInput($_POST['name']);

    $users = getUsers();
    $username = $_SESSION['user']['username'];
    $errors = [];

    // Validate current password
    if (!password_verify($currentPassword, $users[$username]['password'])) {
        $errors[] = "Current password is incorrect.";
    }

    // Validate new password if provided
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = "New passwords do not match.";
        }
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($errors)) {
        // Update user data
        $users[$username]['email'] = $email;
        $users[$username]['name'] = $name;

        // Update password if provided
        if (!empty($newPassword)) {
            $users[$username]['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        saveUsers($users);

        // Update session
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['name'] = $name;

        setMessage("Profile updated successfully!", 'success');
        redirect('profile.php');
    }
}

// Get current user data
$users = getUsers();
$currentUser = $users[$_SESSION['user']['username']];
$userTasks = json_decode(file_get_contents(__DIR__ . '/data/tasks.json'), true) ?: [];
$userTasks = array_filter($userTasks, function ($task) {
    return $task['username'] === $_SESSION['user']['username'];
});
?>

<div class="profile-container">
    <div class="profile-sidebar">
        <div class="card">
            <div class="profile-avatar">
                <div class="avatar-placeholder">
                    <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 2)); ?>
                </div>
            </div>
            <div class="profile-info">
                <h3><?php echo $_SESSION['user']['name']; ?></h3>
                <p class="profile-username">@<?php echo $_SESSION['user']['username']; ?></p>
                <p class="profile-email"><?php echo $_SESSION['user']['email']; ?></p>
                <p class="profile-role">Role: <?php echo ucfirst($_SESSION['user']['role']); ?></p>
            </div>
        </div>

        <div class="card">
            <h4>Quick Stats</h4>
            <div class="profile-stats">
                <div class="profile-stat">
                    <span class="stat-number"><?php echo count($userTasks); ?></span>
                    <span class="stat-label">Total Tasks</span>
                </div>
                <div class="profile-stat">
                    <span class="stat-number">
                        <?php
                        $completed = count(array_filter($userTasks, function ($task) {
                            return $task['status'] === 'completed';
                        }));
                        echo $completed;
                        ?>
                    </span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-content">
        <div class="card">
            <h2>Edit Profile</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="profile.php">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?php echo $currentUser['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="<?php echo $currentUser['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="current_password">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                    <small>Enter your current password to make changes</small>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control">
                    <small>Leave blank if you don't want to change your password</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <div class="card">
            <h2>Account Information</h2>
            <div class="account-info">
                <div class="info-row">
                    <strong>Username:</strong>
                    <span><?php echo $_SESSION['user']['username']; ?></span>
                </div>
                <div class="info-row">
                    <strong>Member Since:</strong>
                    <span><?php echo $currentUser['registered_at'] ?? 'Unknown'; ?></span>
                </div>
                <div class="info-row">
                    <strong>Last Login:</strong>
                    <span><?php echo $_SESSION['user']['last_login'] ?? 'Unknown'; ?></span>
                </div>
                <div class="info-row">
                    <strong>Account Type:</strong>
                    <span><?php echo ucfirst($_SESSION['user']['role']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
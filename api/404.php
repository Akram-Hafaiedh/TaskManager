<?php
$pageTitle = "Page Not Found";
http_response_code(404);
require_once 'includes/header.php';
?>

<div class="error-container">
    <div class="error-content">
        <div class="error-code">404</div>
        <h1>Page Not Found</h1>
        <p>Sorry, the page you are looking for doesn't exist or has been moved.</p>

        <div class="error-actions">
            <a href="index.php" class="btn btn-primary">
                ← Go Back Home
            </a>
            <a href="javascript:history.back()" class="btn btn-outline">
                ← Return to Previous Page
            </a>
        </div>

        <div class="error-help">
            <h3>What you can do:</h3>
            <ul>
                <li>Check the URL for typos</li>
                <li>Go back to the previous page</li>
                <li>Visit our homepage</li>
                <li>Contact support if you believe this is an error</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
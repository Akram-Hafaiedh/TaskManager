<?php
$pageTitle = "Contact";
require_once 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);

    // In a real app, you'd send an email or save to database
    // For demo, we'll just show a success message
    setMessage("Thank you, $name! Your message has been sent. We'll get back to you soon.", 'success');
    redirect('contact.php');
}
?>

<div class="card">
    <h1>Contact Us</h1>
    <p>Have questions or feedback? We'd love to hear from you!</p>

    <div class="contact-container">
        <div class="contact-form">
            <h2>Send us a Message</h2>
            <form method="POST" action="contact.php">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required
                        value="<?php echo $_POST['name'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" required
                        value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" class="form-control" required>
                        <option value="">Select a subject</option>
                        <option value="general" <?php echo ($_POST['subject'] ?? '') === 'general' ? 'selected' : ''; ?>>General Inquiry</option>
                        <option value="support" <?php echo ($_POST['subject'] ?? '') === 'support' ? 'selected' : ''; ?>>Technical Support</option>
                        <option value="feature" <?php echo ($_POST['subject'] ?? '') === 'feature' ? 'selected' : ''; ?>>Feature Request</option>
                        <option value="bug" <?php echo ($_POST['subject'] ?? '') === 'bug' ? 'selected' : ''; ?>>Bug Report</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" class="form-control" rows="5" required
                        placeholder="Tell us how we can help you..."><?php echo $_POST['message'] ?? ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>

        <div class="contact-info">
            <h2>Get in Touch</h2>
            <div class="contact-method">
                <h3>📧 Email</h3>
                <p>support@taskmaster.com</p>
            </div>
            <div class="contact-method">
                <h3>🕒 Support Hours</h3>
                <p>Monday - Friday: 9AM - 6PM</p>
                <p>Response Time: Within 24 hours</p>
            </div>
            <div class="contact-method">
                <h3>🔧 Technical Stack</h3>
                <p>PHP <?php echo phpversion(); ?></p>
                <p>Hosted on Vercel</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
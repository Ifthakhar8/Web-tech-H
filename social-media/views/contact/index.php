<?php ob_start(); ?>

<div class="contact-container">
    <h1 class="contact-title">Contact Us</h1>
    <p class="contact-description">Have a question or feedback? We'd love to hear from you!</p>

    <?php if (isset($success_message) && $success_message == 'true'): ?>
        <div class="success-message">
            <p>Thank you for your message! We will get back to you shortly.</p>
            <p><a href="index.php?action=home">Go back to Home</a></p>
        </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'missing_fields'): ?>
        <div class="error-message">
            <p>Please fill in all required fields.</p>
        </div>
    <?php endif; ?>

    <div class="contact-form-card">
        <form action="?action=submit_contact" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required placeholder="John Doe">
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com">
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required placeholder="Inquiry about...">
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required placeholder="Your message here..."></textarea>
            </div>

            <button type="submit" class="btn contact-submit-btn">Send Message</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
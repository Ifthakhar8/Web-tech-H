<?php ob_start(); ?>

<div class="auth-container">
    <h2>Delete Account</h2>
    <p class="error-message" style="display: <?= !empty($error) ? 'block' : 'none'; ?>;"><?= htmlspecialchars($error ?? '') ?></p>
    <p class="success-message" style="display: <?= !empty($success) ? 'block' : 'none'; ?>;"><?= htmlspecialchars($success ?? '') ?></p>

    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
    <p>All your posts, comments, and likes will be permanently removed.</p>

    <form method="POST" action="?action=delete_account_confirm" class="auth-form">
        <div class="form-group">
            <label for="password">Enter your password to confirm:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn delete-btn">Confirm Deletion</button>
    </form>
    <div class="auth-link">
        <a href="index.php">Go back to Home</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
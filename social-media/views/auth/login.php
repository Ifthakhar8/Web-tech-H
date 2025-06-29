<?php ob_start(); ?>
<div class="auth-container">
    <h2>Login to Your Account</h2>
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="?action=login">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <p class="auth-link">Don't have an account? <a href="?action=register">Register here</a></p>
</div>
<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
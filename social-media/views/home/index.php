<?php ob_start(); ?>
<div class="header">
    <h1>Social Students Dashboard</h1>
    <p>Connect with Aiubians</p>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="header-user-info" style="text-align: center; margin-top: 10px;">
            <span class="welcome-text" style="font-size: 1.1rem; color: #555;">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</span>
            <a href="?action=logout" class="logout-btn btn" style="display: inline-block; padding: 8px 15px; margin-left: 15px; background: #dc3545; font-size: 0.9rem; border-radius: 5px;">Logout</a>
            <a href="?action=delete_account" class="delete-account-btn btn" style="display: inline-block; padding: 8px 15px; margin-left: 15px; background: #ffc107; color: #333; font-size: 0.9rem; border-radius: 5px; text-decoration: none;">Delete Account</a>
            <a href="?action=contact" class="contact-btn btn" style="display: inline-block; padding: 8px 15px; margin-left: 15px; background: #28a745; font-size: 0.9rem; border-radius: 5px; text-decoration: none;">Contact Us</a>
        </div>
    <?php endif; ?>

    <div class="search-bar" style="margin-top: 20px; text-align: center;">
        <form method="GET" action="?action=search" style="display: flex; justify-content: center; gap: 10px;">
            <input
                type="text"
                name="search_query"
                placeholder="Search posts or users..."
                value="<?= htmlspecialchars($_GET['search_query'] ?? '') ?>"
                style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; flex-grow: 1; max-width: 400px;"
            >
            <button type="submit" class="btn search-btn" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Search</button>
        </form>
    </div>

</div>

<div class="post-form">
    <h2 style="margin-bottom: 20px; color: #333;">Create New Post</h2>
    <form method="POST" action="?action=create_post">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">
        <div class="form-group">
            <label for="content">What's on your mind?</label>
            <textarea name="content" id="content" rows="4" placeholder="Share something interesting..." required></textarea>
        </div>
        <button type="submit" class="btn">Share Post</button>
    </form>
</div>

<div class="posts">
    <?php foreach($posts as $post): ?>
        <div class="post" data-post-id="<?= $post['id'] ?>">
            <div class="post-header">
                <div class="avatar"><?= strtoupper(substr($post['username'], 0, 2)) ?></div>
                <div class="post-info">
                    <h3><?= htmlspecialchars($post['username']) ?></h3>
                    <div class="post-time"><?= date('M j, Y • g:i A', strtotime($post['created_at'])) ?></div>
                </div>
            </div>

            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <div class="post-actions">
                <form method="POST" action="?action=like_post" style="display: inline;">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">
                    <button type="submit" class="action-btn like-btn">
                        ❤️ <?= $post['likes_count'] ?> Likes
                    </button>
                </form>

                <button class="action-btn comment-btn" onclick="toggleComments(<?= $post['id'] ?>)">
                    💬 <?= $post['comments_count'] ?> Comments
                </button>
            </div>

            <div class="comments hidden" id="comments-<?= $post['id'] ?>">
                <?php
                
                if (isset($commentModel)) {
                    $comments = $commentModel->getCommentsByPost($post['id']);
                    foreach($comments as $comment):
                ?>
                    <div class="comment">
                        <div class="comment-avatar"><?= strtoupper(substr($comment['username'], 0, 2)) ?></div>
                        <div class="comment-content">
                            <div class="comment-author"><?= htmlspecialchars($comment['username']) ?></div>
                            <div class="comment-text"><?= htmlspecialchars($comment['content']) ?></div>
                        </div>
                    </div>
                <?php
                    endforeach;
                } else {
                 
                    echo "<p>Comments cannot be loaded: Comment model not available.</p>";
                }
                ?>

                <div class="comment-form">
                    <form method="POST" action="?action=add_comment" style="display: flex; width: 100%; gap: 10px;">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">
                        <input type="text" name="content" class="comment-input" placeholder="Write a comment..." required>
                        <button type="submit" class="comment-submit">Post</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();

include 'views/layouts/main.php';
?>

function toggleComments(postId) {
    const commentsDiv = document.getElementById('comments-' + postId);
    commentsDiv.classList.toggle('hidden');
}


document.addEventListener('DOMContentLoaded', function() {
    const contentTextarea = document.getElementById('content');
    if (contentTextarea) {
        contentTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
    

    document.querySelectorAll('.post').forEach(post => {
        post.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        post.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="?action=create_post"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const userId = document.getElementById('user_id');
            const content = document.getElementById('content');
            
            if (userId && content && (!userId.value || !content.value.trim())) {
                e.preventDefault();
                alert('Please select a user and enter some content!');
            }
        });
    }
});
<?php

class HomeController {
    private $postModel;
    private $userModel;
    private $commentModel;
    
    public function __construct() {
        $this->postModel = new Post();
        $this->userModel = new User();
        $this->commentModel = new Comment();
    }
    
    public function index() {
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $posts = $this->postModel->getAllPosts();
        $users = $this->userModel->getAllUsers(); 
        $commentModel = $this->commentModel;
        include 'views/home/index.php';
    }
    
    public function createPost() {
        if (!isset($_SESSION['user_id'])) { 
            header('Location: index.php?action=login'); exit;
        }
        $userId = $_SESSION['user_id']; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
            $content = $_POST['content'];
            $this->postModel->createPost($userId, $content); 
        }
        header('Location: index.php');
        exit;
    }
    
    public function likePost() {
        if (!isset($_SESSION['user_id'])) { 
            header('Location: index.php?action=login'); exit;
        }
        $userId = $_SESSION['user_id']; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
            $postId = $_POST['post_id'];
            
            if ($this->postModel->isLikedByUser($postId, $userId)) {
                $this->postModel->unlikePost($postId, $userId);
            } else {
                $this->postModel->likePost($postId, $userId);
            }
        }
        header('Location: index.php');
        exit;
    }
    
    public function addComment() {
        if (!isset($_SESSION['user_id'])) { 
            header('Location: index.php?action=login'); exit;
        }
        $userId = $_SESSION['user_id']; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id']) && isset($_POST['content'])) {
            $postId = $_POST['post_id'];
            $content = $_POST['content'];
            $this->commentModel->createComment($postId, $userId, $content);
        }
        header('Location: index.php');
        exit;
    }


   public function searchPosts() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $searchTerm = $_GET['search_query'] ?? '';
        $posts = [];
        if (!empty($searchTerm)) {
            $posts = $this->postModel->searchPosts($searchTerm);
        } else {
            $posts = $this->postModel->getAllPosts();
        }
        
        $users = $this->userModel->getAllUsers();
        $commentModel = $this->commentModel;
        include 'views/home/index.php';
    }
}
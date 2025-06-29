<?php

class Post {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPosts() {
        $sql = "SELECT p.*, u.username, u.avatar,
                (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) as likes_count,
                (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) as comments_count
                FROM posts p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($id) {
        $sql = "SELECT p.*, u.username, u.avatar FROM posts p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPost($userId, $content, $image = null) {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $content, $image]);
    }

    public function likePost($postId, $userId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            return $stmt->execute([$postId, $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function unlikePost($postId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }

    public function isLikedByUser($postId, $userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function searchPosts($searchTerm) {
        $searchTerm = '%' . $searchTerm . '%';

        $sql = "SELECT p.*, u.username, u.avatar,
                (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) as likes_count,
                (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) as comments_count
                FROM posts p
                JOIN users u ON p.user_id = u.id
                WHERE p.content LIKE ? OR u.username LIKE ?
                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
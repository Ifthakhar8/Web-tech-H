<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, email, avatar FROM users ORDER BY username");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, password, avatar FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUser($userId) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM comments WHERE user_id = ?");
            $stmt->execute([$userId]);

            
            $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = ?");
            $stmt->execute([$userId]);

            $stmt = $this->db->prepare("SELECT id FROM posts WHERE user_id = ?");
            $stmt->execute([$userId]);
            $userPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($userPosts as $post) {
               
                $stmt = $this->db->prepare("DELETE FROM comments WHERE post_id = ?");
                $stmt->execute([$post['id']]);
              
                $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ?");
                $stmt->execute([$post['id']]);
            }

          
            $stmt = $this->db->prepare("DELETE FROM posts WHERE user_id = ?");
            $stmt->execute([$userId]);

           
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $success = $stmt->execute([$userId]);

            $this->db->commit();
            return $success;

        } catch (PDOException $e) {
            $this->db->rollBack();
            
            error_log("Error deleting user {$userId}: " . $e->getMessage());
            return false;
        }
    }
}
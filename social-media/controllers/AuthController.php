<?php

require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                header('Location: index.php');
                exit;
            } else {
                $error = "Invalid email or password.";
                include 'views/auth/login.php';
            }
        } else {
            include 'views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                $error = "All fields are required.";
                include 'views/auth/register.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
                include 'views/auth/register.php';
                return;
            }

            if ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
                include 'views/auth/register.php';
                return;
            }

            if ($this->userModel->getUserByEmail($email)) {
                $error = "Email already registered.";
                include 'views/auth/register.php';
                return;
            }
            if ($this->userModel->getUserByUsername($username)) {
                $error = "Username already taken.";
                include 'views/auth/register.php';
                return;
            }

            if ($this->userModel->createUser($username, $email, $password)) {
                session_start();
                $user = $this->userModel->getUserByEmail($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                header('Location: index.php');
                exit;
            } else {
                $error = "Registration failed. Please try again.";
                include 'views/auth/register.php';
            }
        } else {
            include 'views/auth/register.php';
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public function showDeleteAccountForm() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $error = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);
        $success = $_SESSION['success_message'] ?? '';
        unset($_SESSION['success_message']);
        include 'views/account/delete.php';
    }

    public function deleteAccountConfirm() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            $userId = $_SESSION['user_id'];
            $password = $_POST['password'];

            $user = $this->userModel->getUserById($userId);

            if ($user && password_verify($password, $user['password'])) {
                if ($this->userModel->deleteUser($userId)) {
                    session_destroy();
                    session_start();
                    $_SESSION['success_message'] = "Your account has been successfully deleted.";
                    header('Location: index.php?action=login');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error deleting account. Please try again.";
                    header('Location: index.php?action=delete_account');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Incorrect password. Please try again.";
                header('Location: index.php?action=delete_account');
                exit;
            }
        } else {
            header('Location: index.php?action=delete_account');
            exit;
        }
    }
}
?>
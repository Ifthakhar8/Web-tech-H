<?php

class ContactController {
    public function index() {
        $success_message = $_GET['success'] ?? null;
        include 'views/contact/index.php';
    }

    public function submit() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            header('Location: index.php?action=contact&error=missing_fields');
            exit;
        }

        header('Location: index.php?action=contact&success=true');
        exit;
    }
}
<?php
session_start();

spl_autoload_register(function ($class) {
    if (file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    } elseif (file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    } elseif (file_exists('config/' . $class . '.php')) {
        require_once 'config/' . $class . '.php';
    }
});

Database::getInstance();

$action = $_GET['action'] ?? 'home';

if (isset($_GET['search_query']) && $action !== 'search') {
    $action = 'search';
}

switch ($action) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'home':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new HomeController();
        $controller->index();
        break;
    case 'search':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new HomeController();
        $controller->searchPosts();
        break;
    case 'create_post':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new HomeController();
        $controller->createPost();
        break;
    case 'like_post':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new HomeController();
        $controller->likePost();
        break;
    case 'add_comment':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new HomeController();
        $controller->addComment();
        break;
    case 'contact':
        $controller = new ContactController();
        $controller->index();
        break;
    case 'submit_contact':
        $controller = new ContactController();
        $controller->submit();
        break;
    case 'delete_account':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new AuthController();
        $controller->showDeleteAccountForm();
        break;
    case 'delete_account_confirm':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $controller = new AuthController();
        $controller->deleteAccountConfirm();
        break;
    default:
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
        } else {
            header('Location: index.php?action=home');
        }
        exit;
}
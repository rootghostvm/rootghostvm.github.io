<?php
require_once __DIR__ . '/../config.php';

function loginUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    
    return false;
}

function logoutUser() {
    session_unset();
    session_destroy();
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/admin/login.php');
    }
}

<?php
require_once 'db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function authenticateUser($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user['username'];
        return true;
    }
    return false;
}

function login($username, $password) {
    return authenticateUser($username, $password);
}

function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}

function checkAdmin() {
    if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }
}
?>
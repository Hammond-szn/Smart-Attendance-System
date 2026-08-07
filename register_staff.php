<?php
require_once __DIR__ . '/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $name = trim($_POST['name'] ?? '');
    if ($username === '' || $password === '') {
        echo 'Username and password required.'; exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO staff (username, password, name) VALUES (:u, :p, :n)');
    try {
        $stmt->execute([':u'=>$username, ':p'=>$hash, ':n'=>$name]);
        $id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = 'staff';
        $_SESSION['username'] = $username;
        header('Location: staff_dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
}
?>

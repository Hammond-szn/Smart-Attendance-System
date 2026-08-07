<?php
require_once __DIR__ . '/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $number = trim($_POST['number'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $rollno = trim($_POST['rollno'] ?? '');
    if ($username === '' || $password === '') {
        echo 'Username and password required.'; exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO students (username, password, name, number, city, rollno) VALUES (:u, :p, :n, :num, :c, :r)');
    try {
        $stmt->execute([':u'=>$username, ':p'=>$hash, ':n'=>$name, ':num'=>$number, ':c'=>$city, ':r'=>$rollno]);
        $id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = 'student';
        $_SESSION['username'] = $username;
        header('Location: student_dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
}
?>

<?php
require_once __DIR__ . '/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'staff';
    if ($username === '' || $password === '') {
        echo 'Username and password required.'; exit;
    }
    if ($role === 'staff') {
        $stmt = $pdo->prepare('SELECT * FROM staff WHERE username = :u LIMIT 1');
    } else {
        $stmt = $pdo->prepare('SELECT * FROM students WHERE username = :u LIMIT 1');
    }
    $stmt->execute([':u'=>$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = ($role === 'staff') ? 'staff' : 'student';
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'] ?? '';
        if ($_SESSION['role'] === 'staff') {
            header('Location: staff_dashboard.php');
        } else {
            header('Location: student_dashboard.php');
        }
        exit;
    } else {
        echo 'Invalid credentials.';
    }
}
?>

<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

session_start();

$errors = [];
$old = ['username' => '', 'name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $name     = trim((string)($_POST['name'] ?? ''));

    $old = ['username' => $username, 'name' => $name];

    // --- Validation ---
    if ($username === '' || $password === '') {
        $errors[] = 'Username and password are required.';
    }
    if ($username !== '' && !preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3-30 characters (letters, numbers, _ . -).';
    }
    if ($password !== '' && strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {
        try {
            // Check for duplicate username first
            $check = $pdo->prepare('SELECT COUNT(*) FROM staff WHERE username = :u');
            $check->execute([':u' => $username]);

            if ((int)$check->fetchColumn() > 0) {
                $errors[] = 'That username is already taken.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $insert = $pdo->prepare(
                    'INSERT INTO staff (username, password, name) VALUES (:u, :p, :n)'
                );
                $insert->execute([
                    ':u' => $username,
                    ':p' => $hash,
                    ':n' => $name,
                ]);

                // Prevent session fixation
                session_regenerate_id(true);

                $_SESSION['user_id']  = (int)$pdo->lastInsertId();
                $_SESSION['role']     = 'Staff';
                $_SESSION['username'] = $username;

                header('Location: staff_dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            // Log the real error, show a generic message to the user
            error_log('Staff registration error: ' . $e->getMessage());
            $errors[] = 'Something went wrong. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Registration</title>
</head>
<body>
    <h2>Register as Staff</h2>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <label>Full Name:
            <input type="text" name="name"
                   value="<?= htmlspecialchars($old['name'], ENT_QUOTES, 'UTF-8') ?>">
        </label><br><br>

        <label>Username:
            <input type="text" name="username" required
                   value="<?= htmlspecialchars($old['username'], ENT_QUOTES, 'UTF-8') ?>">
        </label><br><br>

        <label>Password:
            <input type="password" name="password" required minlength="8">
        </label><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>

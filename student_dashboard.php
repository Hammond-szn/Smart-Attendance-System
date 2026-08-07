<?php
require_once __DIR__ . '/db.php';
session_start();
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header('Location: index.html'); exit;
}
$stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $_SESSION['user_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Student Dashboard</title>
<link rel="stylesheet" href="style.css">
</head><body>
<h1>Student Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> — <a href="logout.php">Logout</a></p>
<?php if ($student): ?>
  <ul>
    <li>Name: <?php echo htmlspecialchars($student['name']); ?></li>
    <li>Number: <?php echo htmlspecialchars($student['number']); ?></li>
    <li>City: <?php echo htmlspecialchars($student['city']); ?></li>
    <li>Roll No: <?php echo htmlspecialchars($student['rollno']); ?></li>
  </ul>
<?php else: ?>
  <p>Profile not found.</p>
<?php endif; ?>
</body></html>

<?php
require_once __DIR__ . '/db.php';
session_start();
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header('Location: index.html'); exit;
}
// Fetch lists
$students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$staffs = $pdo->query('SELECT * FROM staff ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Staff Dashboard</title>
<link rel="stylesheet" href="style.css">
</head><body>
<h1>Staff Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> — <a href="logout.php">Logout</a></p>
<h2>Staffs</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Username</th><th>Name</th></tr>
<?php foreach ($staffs as $s): ?>
<tr><td><?php echo $s['id']; ?></td><td><?php echo htmlspecialchars($s['username']); ?></td><td><?php echo htmlspecialchars($s['name']); ?></td></tr>
<?php endforeach; ?>
</table>
<p><a href="staff_register.html">Add staff</a> | <a href="student_register.html">Add student</a></p>
<h2>Students</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Username</th><th>Name</th><th>Number</th><th>City</th><th>Roll No.</th></tr>
<?php foreach ($students as $st): ?>
<tr>
  <td><?php echo $st['id']; ?></td>
  <td><?php echo htmlspecialchars($st['username']); ?></td>
  <td><?php echo htmlspecialchars($st['name']); ?></td>
  <td><?php echo htmlspecialchars($st['number']); ?></td>
  <td><?php echo htmlspecialchars($st['city']); ?></td>
  <td><?php echo htmlspecialchars($st['rollno']); ?></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>

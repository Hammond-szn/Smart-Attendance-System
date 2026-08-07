<?php
// SQLite DB connection and initialization
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}
$dbFile = $dataDir . '/attendance.db';
try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Create tables if not exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS staff (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT,
        name TEXT
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT,
        name TEXT,
        number TEXT,
        city TEXT,
        rollno TEXT
    )");
} catch (PDOException $e) {
    echo "DB Error: " . htmlspecialchars($e->getMessage());
    exit;
}

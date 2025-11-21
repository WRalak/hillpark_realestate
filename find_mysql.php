<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Finding MySQL Configuration</h2>";

// Common XAMPP MySQL socket paths
$socket_paths = [
    'C:/xampp/mysql/mysql.sock',
    'C:/xampp/mysql/data/mysql.sock',
    '/xampp/mysql/mysql.sock',
    '/tmp/mysql.sock'
];

foreach($socket_paths as $socket) {
    if (file_exists($socket)) {
        echo "<p style='color: green;'>✅ MySQL socket found: $socket</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Socket not found: $socket</p>";
    }
}

// Try connecting with different sockets
echo "<h3>Testing socket connections:</h3>";

$socket_tests = [
    "mysql:host=localhost;unix_socket=C:/xampp/mysql/mysql.sock",
    "mysql:host=127.0.0.1",
    "mysql:host=localhost"
];

foreach($socket_tests as $dsn) {
    try {
        $pdo = new PDO($dsn, "root", "");
        echo "<p style='color: green;'>✅ Connected with: $dsn</p>";
    } catch(PDOException $e) {
        echo "<p style='color: red;'>❌ Failed: $dsn - " . $e->getMessage() . "</p>";
    }
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>MySQL/MariaDB Connection Test</h2>";

// Test different connection methods
$connections = [
    ['mysql:host=localhost', 'root', ''],
    ['mysql:host=127.0.0.1', 'root', ''],
    ['mysql:host=localhost;port=3306', 'root', ''],
    ['mysql:host=127.0.0.1;port=3306', 'root', ''],
];

foreach($connections as $conn) {
    list($dsn, $user, $pass) = $conn;
    echo "<h3>Trying: $dsn</h3>";
    
    try {
        $pdo = new PDO($dsn, $user, $pass);
        echo "<p style='color: green;'>✅ Connected successfully!</p>";
        
        // Show MySQL version
        $version = $pdo->query('SELECT VERSION()')->fetchColumn();
        echo "<p>MySQL Version: $version</p>";
        
        // Show databases
        echo "<p>Databases:</p>";
        $databases = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
        foreach($databases as $db) {
            echo "- $db<br>";
        }
        break;
        
    } catch(PDOException $e) {
        echo "<p style='color: red;'>❌ Failed: " . $e->getMessage() . "</p>";
    }
}

// Test if we can create database without specifying one
echo "<h3>Testing raw connection:</h3>";
try {
    $pdo = new PDO("mysql:host=127.0.0.1", "root", "");
    echo "<p style='color: green;'>✅ Raw connection successful</p>";
    
    // Create database directly
    $pdo->exec("CREATE DATABASE IF NOT EXISTS hillpark_db");
    echo "<p style='color: green;'>✅ Database 'hillpark_db' created</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Raw connection failed: " . $e->getMessage() . "</p>";
}
?>
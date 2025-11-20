<?php
// test_connection.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Test</h1>";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=hillpark_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    
    // Check if tables exist
    $tables = ['users', 'properties', 'inquiries'];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Table '$table' exists!</p>";
            
            // Count rows in each table
            $count = $pdo->query("SELECT COUNT(*) as count FROM $table")->fetch()['count'];
            echo "<p>Rows in $table: $count</p>";
        } else {
            echo "<p style='color: red;'>❌ Table '$table' is MISSING!</p>";
        }
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test config.php
echo "<h2>Testing config.php</h2>";
include 'config.php';
echo "<p>Database name: " . DB_NAME . "</p>";
?>

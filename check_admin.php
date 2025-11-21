<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

echo "<h2>Checking Admin User</h2>";

try {
    // Check if admin user exists
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new PDOException('Database connection $pdo is not initialized or is not a PDO instance. Check config.php.');
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
    $stmt->execute();
    $user = $stmt->fetch();
    
    if($user) {
        echo "<p style='color: green;'>✅ Admin user found in database</p>";
        echo "<p>Username: " . $user['username'] . "</p>";
        echo "<p>Role: " . $user['role'] . "</p>";
        echo "<p>Password hash: " . $user['password'] . "</p>";
        
        // Test password verification
        $test_password = 'admin123';
        if(password_verify($test_password, $user['password'])) {
            echo "<p style='color: green;'>✅ Password 'admin123' verifies correctly</p>";
        } else {
            echo "<p style='color: red;'>❌ Password 'admin123' does NOT verify</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Admin user not found in database</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

if (!isset($pdo) || !$pdo) {
    die("<p style='color: red;'>❌ Database connection failed. Please check your config.php settings.</p>");
}

echo "<h2>Resetting Admin Password</h2>";

try {
    // Delete existing admin user
    $pdo->exec("DELETE FROM users WHERE username = 'admin'");
    echo "<p>Removed existing admin user</p>";
    
    // Create new admin user with proper password hash
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute(['admin', $hashed_password]);
    
    echo "<p style='color: green;'>✅ Admin user created successfully!</p>";
    echo "<p>Username: <strong>admin</strong></p>";
    echo "<p>Password: <strong>admin123</strong></p>";
    echo "<p>Hashed password: " . $hashed_password . "</p>";
    
    // Verify the password works
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = 'admin'");
    $stmt->execute();
    $stored_hash = $stmt->fetchColumn();
    
    if(password_verify('admin123', $stored_hash)) {
        echo "<p style='color: green;'>✅ Password verification successful!</p>";
    } else {
        echo "<p style='color: red;'>❌ Password verification failed!</p>";
    }
    
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
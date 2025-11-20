<?php
include 'config.php';

echo "<h2>Admin Password Reset</h2>";

// Check if admin user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch();

if($user) {
    echo "<p>Admin user exists. Current password hash: " . $user['password'] . "</p>";
} else {
    echo "<p>Admin user not found. Creating one...</p>";
}

// Reset or create admin user with proper password hash
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Delete existing admin
$pdo->exec("DELETE FROM users WHERE username = 'admin'");

// Create new admin user
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$stmt->execute(['admin', $hashed_password]);

echo "<p style='color: green;'>✅ Admin user reset successfully!</p>";
echo "<p>Username: <strong>admin</strong></p>";
echo "<p>Password: <strong>admin123</strong></p>";
echo "<p>Hashed password: " . $hashed_password . "</p>";

// Test the login
if(password_verify('admin123', $hashed_password)) {
    echo "<p style='color: green;'>✅ Password verification successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Password verification failed!</p>";
}

echo "<p><a href='login.php'>Go to Login Page</a></p>";
?>
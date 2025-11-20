<?php
include 'config.php';

// Simple login without password hashing
if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Simple check - no hashing
    if($username === 'admin' && $password === 'admin123') {
        // Get or create admin user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if(!$user) {
            // Create admin user if doesn't exist
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
            $stmt->execute([$username, $password]); // Store plain text (not recommended for production)
            $user_id = $pdo->lastInsertId();
            
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
        }
        
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="login-form">
        <h2>Simple Admin Login</h2>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="admin" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" value="admin123" required>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
        
        <div class="login-info">
            <p><strong>Default Login:</strong></p>
            <p>Username: <code>admin</code></p>
            <p>Password: <code>admin123</code></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
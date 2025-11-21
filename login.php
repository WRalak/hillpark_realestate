<?php
include 'config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';

if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    try {
        // Ensure $pdo is a valid PDO instance before using it
        if (!isset($pdo) || !($pdo instanceof PDO)) {
            throw new Exception('Database connection is not initialized.');
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if($user) {
            // Debug info (remove in production)
            echo "<!-- User found: " . $user['username'] . " -->";
            echo "<!-- Password verify result: " . (password_verify($password, $user['password']) ? 'true' : 'false') . " -->";
            
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect to admin dashboard
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid password. Try 'admin123'";
            }
        } else {
            $error = "User not found. Try 'admin'";
        }
    } catch(Exception $e) {
        $error = "Login error: " . $e->getMessage();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="simple-login">
        <h2>Admin Login</h2>
        
        <?php if($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
                <br><small><a href="reset_admin.php">Reset admin password</a></small>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="login-form">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" value="admin" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" value="admin123" required>
            </div>
            
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>
        
        <div class="login-info">
            <p><strong>Default credentials:</strong></p>
            <p>Username: <strong>admin</strong></p>
            <p>Password: <strong>admin123</strong></p>
            <p><a href="reset_admin.php">Reset Admin Password</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
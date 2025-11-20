<?php
include 'config.php';

if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } catch(Exception $e) {
        $error = "Login error. Please try again.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="simple-login">
        <h2>Admin Login</h2>
        
        <?php if(isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
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
            <p><strong>Default login:</strong></p>
            <p>Username: <strong>admin</strong></p>
            <p>Password: <strong>admin123</strong></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
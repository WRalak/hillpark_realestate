<?php
include '../config.php';

// Check if user is logged in and is admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get statistics
$total_properties = $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
$available_properties = $pdo->query("SELECT COUNT(*) FROM properties WHERE status = 'available'")->fetchColumn();
$sold_properties = $pdo->query("SELECT COUNT(*) FROM properties WHERE status = 'sold'")->fetchColumn();
$total_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Dashboard</h1>
        <p>Welcome back, <?php echo $_SESSION['username']; ?>!</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Properties</h3>
                <p class="stat-number"><?php echo $total_properties; ?></p>
            </div>
            <div class="stat-card">
                <h3>Available Properties</h3>
                <p class="stat-number"><?php echo $available_properties; ?></p>
            </div>
            <div class="stat-card">
                <h3>Sold Properties</h3>
                <p class="stat-number"><?php echo $sold_properties; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Inquiries</h3>
                <p class="stat-number"><?php echo $total_inquiries; ?></p>
            </div>
        </div>
        
        <div class="recent-activities">
            <h2>Recent Properties</h2>
            <?php
            $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC LIMIT 5");
            $recent_properties = $stmt->fetchAll();
            ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_properties as $property): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($property['title']); ?></td>
                        <td>$<?php echo number_format($property['price']); ?></td>
                        <td><?php echo ucfirst($property['type']); ?></td>
                        <td><?php echo ucfirst($property['status']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($property['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
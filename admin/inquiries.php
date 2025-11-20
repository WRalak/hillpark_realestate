<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("SELECT inquiries.*, properties.title as property_title FROM inquiries LEFT JOIN properties ON inquiries.property_id = properties.id ORDER BY inquiries.created_at DESC");
$inquiries = $stmt->fetchAll();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Property Inquiries</h1>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Property</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($inquiries as $inquiry): ?>
                <tr>
                    <td><?php echo $inquiry['name']; ?></td>
                    <td><?php echo $inquiry['email']; ?></td>
                    <td><?php echo $inquiry['phone']; ?></td>
                    <td><?php echo $inquiry['property_title']; ?></td>
                    <td><?php echo substr($inquiry['message'], 0, 50) . '...'; ?></td>
                    <td><?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
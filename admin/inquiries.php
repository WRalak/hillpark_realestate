<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT inquiries.*, properties.title as property_title 
    FROM inquiries 
    LEFT JOIN properties ON inquiries.property_id = properties.id 
    ORDER BY inquiries.created_at DESC
");
$inquiries = $stmt->fetchAll();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Property Inquiries</h1>
        
        <?php if(count($inquiries) > 0): ?>
            <div class="inquiries-list">
                <?php foreach($inquiries as $inquiry): ?>
                <div class="inquiry-card">
                    <div class="inquiry-header">
                        <h3>Inquiry for: <?php echo $inquiry['property_title'] ?: 'General Inquiry'; ?></h3>
                        <span class="inquiry-date"><?php echo date('M j, Y g:i A', strtotime($inquiry['created_at'])); ?></span>
                    </div>
                    
                    <div class="inquiry-details">
                        <div class="contact-info">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($inquiry['name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($inquiry['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo $inquiry['phone'] ? htmlspecialchars($inquiry['phone']) : 'Not provided'; ?></p>
                        </div>
                        
                        <div class="inquiry-message">
                            <p><strong>Message:</strong></p>
                            <p><?php echo nl2br(htmlspecialchars($inquiry['message'])); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No inquiries yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle inquiry deletion
if(isset($_GET['delete'])) {
    $inquiry_id = (int)$_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    if($stmt->execute([$inquiry_id])) {
        $success = "Inquiry deleted successfully.";
    } else {
        $error = "Error deleting inquiry.";
    }
}

// Handle marking as contacted
if(isset($_GET['mark_contacted'])) {
    $inquiry_id = (int)$_GET['mark_contacted'];
    
    $stmt = $pdo->prepare("UPDATE inquiries SET contacted = 1 WHERE id = ?");
    if($stmt->execute([$inquiry_id])) {
        $success = "Inquiry marked as contacted.";
    } else {
        $error = "Error marking inquiry as contacted.";
    }
}

// Handle unmarking as contacted
if(isset($_GET['unmark_contacted'])) {
    $inquiry_id = (int)$_GET['unmark_contacted'];
    
    $stmt = $pdo->prepare("UPDATE inquiries SET contacted = 0 WHERE id = ?");
    if($stmt->execute([$inquiry_id])) {
        $success = "Inquiry unmarked as contacted.";
    } else {
        $error = "Error unmarking inquiry.";
    }
}

$stmt = $pdo->query("
    SELECT inquiries.*, properties.title as property_title 
    FROM inquiries 
    LEFT JOIN properties ON inquiries.property_id = properties.id 
    ORDER BY inquiries.contacted ASC, inquiries.created_at DESC
");
$inquiries = $stmt->fetchAll();

// Count contacted vs new inquiries
$new_inquiries = 0;
$contacted_inquiries = 0;
foreach($inquiries as $inquiry) {
    if($inquiry['contacted']) {
        $contacted_inquiries++;
    } else {
        $new_inquiries++;
    }
}
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Property Inquiries</h1>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if(count($inquiries) > 0): ?>
            <div class="inquiry-stats">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Inquiries</h3>
                        <p class="stat-number"><?php echo count($inquiries); ?></p>
                    </div>
                    <div class="stat-card new-inquiries">
                        <h3>New Inquiries</h3>
                        <p class="stat-number"><?php echo $new_inquiries; ?></p>
                    </div>
                    <div class="stat-card contacted-inquiries">
                        <h3>Contacted</h3>
                        <p class="stat-number"><?php echo $contacted_inquiries; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="inquiries-list">
                <?php foreach($inquiries as $inquiry): 
                    $is_contacted = $inquiry['contacted'];
                    $card_class = $is_contacted ? 'inquiry-card contacted' : 'inquiry-card new';
                ?>
                <div class="<?php echo $card_class; ?>">
                    <div class="inquiry-header">
                        <div class="inquiry-title">
                            <div class="inquiry-status">
                                <?php if($is_contacted): ?>
                                    <span class="status-badge status-contacted">✅ Contacted</span>
                                <?php else: ?>
                                    <span class="status-badge status-new">🆕 New Inquiry</span>
                                <?php endif; ?>
                            </div>
                            <h3>Inquiry for: <?php echo $inquiry['property_title'] ?: 'General Inquiry'; ?></h3>
                            <span class="inquiry-date"><?php echo date('M j, Y g:i A', strtotime($inquiry['created_at'])); ?></span>
                        </div>
                        <div class="inquiry-actions">
                            <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>?subject=Re: Your inquiry about <?php echo urlencode($inquiry['property_title'] ?: 'our property'); ?>&body=Dear <?php echo urlencode($inquiry['name']); ?>," 
                               class="btn btn-primary" target="_blank">
                               📧 Reply
                            </a>
                            <?php if($inquiry['phone']): ?>
                                <a href="tel:<?php echo htmlspecialchars($inquiry['phone']); ?>" class="btn btn-secondary">📞 Call</a>
                            <?php endif; ?>
                            
                            <?php if($is_contacted): ?>
                                <a href="inquiries.php?unmark_contacted=<?php echo $inquiry['id']; ?>" class="btn btn-outline">↶ Mark as New</a>
                            <?php else: ?>
                                <a href="inquiries.php?mark_contacted=<?php echo $inquiry['id']; ?>" class="btn btn-success">✓ Mark Contacted</a>
                            <?php endif; ?>
                            
                            <a href="inquiries.php?delete=<?php echo $inquiry['id']; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this inquiry?')">🗑️ Delete</a>
                        </div>
                    </div>
                    
                    <div class="inquiry-details">
                        <div class="contact-info">
                            <div class="contact-item">
                                <strong>👤 Name:</strong> 
                                <span><?php echo htmlspecialchars($inquiry['name']); ?></span>
                            </div>
                            <div class="contact-item">
                                <strong>📧 Email:</strong> 
                                <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>">
                                    <?php echo htmlspecialchars($inquiry['email']); ?>
                                </a>
                            </div>
                            <div class="contact-item">
                                <strong>📞 Phone:</strong> 
                                <?php if($inquiry['phone']): ?>
                                    <a href="tel:<?php echo htmlspecialchars($inquiry['phone']); ?>">
                                        <?php echo htmlspecialchars($inquiry['phone']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="not-provided">Not provided</span>
                                <?php endif; ?>
                            </div>
                            <?php if($inquiry['property_title']): ?>
                            <div class="contact-item">
                                <strong>🏠 Property:</strong> 
                                <a href="../property.php?id=<?php echo $inquiry['property_id']; ?>" target="_blank">
                                    <?php echo htmlspecialchars($inquiry['property_title']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="inquiry-message">
                            <p><strong>💬 Message:</strong></p>
                            <div class="message-content">
                                <?php echo nl2br(htmlspecialchars($inquiry['message'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>No Inquiries Yet</h3>
                <p>When buyers contact you through property forms, their inquiries will appear here.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
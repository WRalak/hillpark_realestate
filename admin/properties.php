<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}


if(isset($_GET['delete'])) {
    $property_id = (int)$_GET['delete'];
    

    $stmt = $pdo->prepare("SELECT image_url FROM properties WHERE id = ?");
    $stmt->execute([$property_id]);
    $property = $stmt->fetch();
    
    
    $stmt = $pdo->prepare("DELETE FROM properties WHERE id = ?");
    if($stmt->execute([$property_id])) {
        
        if($property && !empty($property['image_url']) && strpos($property['image_url'], 'uploads/') === 0) {
            $file_path = '../' . $property['image_url']; // Add ../ to get correct path
            if(file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $success = "Property deleted successfully.";
    } else {
        $error = "Error deleting property.";
    }
}

$stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
$properties = $stmt->fetchAll();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="page-header">
            <h1>Manage Properties</h1>
            <a href="add_property.php" class="btn btn-primary">Add New Property</a>
        </div>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="properties-grid admin-grid">
            <?php foreach($properties as $property): 
                $image_url = getImageUrl($property['image_url']);
            ?>
            <div class="property-card admin-card">
                <div class="property-image-container">
                    <img src="<?php echo $image_url; ?>" 
                         alt="<?php echo htmlspecialchars($property['title']); ?>"
                         class="property-image"
                         onerror="this.src='<?php echo DEFAULT_PROPERTY_IMAGE; ?>'">
                    <?php if($property['featured']): ?>
                        <span class="featured-badge">Featured</span>
                    <?php endif; ?>
                    <span class="status-badge status-<?php echo $property['status']; ?>">
                        <?php echo ucfirst($property['status']); ?>
                    </span>
                </div>
                
                <div class="property-info">
                    <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                    <p class="price">$<?php echo number_format($property['price']); ?></p>
                    <p class="location">📍 <?php echo htmlspecialchars($property['location']); ?></p>
                    
                    <div class="property-features">
                        <span>🛏️ <?php echo $property['bedrooms']; ?> Beds</span>
                        <span>🚿 <?php echo $property['bathrooms']; ?> Baths</span>
                        <span>📐 <?php echo number_format($property['area']); ?> sqft</span>
                    </div>
                    
                    <div class="property-meta">
                        <span class="type-badge type-<?php echo $property['type']; ?>">
                            <?php echo ucfirst($property['type']); ?>
                        </span>
                        <span class="image-source">
                            <?php 
                            if(strpos($property['image_url'], 'uploads/') === 0) {
                                echo '📁 Uploaded';
                            } elseif($property['image_url'] === DEFAULT_PROPERTY_IMAGE || empty($property['image_url'])) {
                                echo '⚙️ Default';
                            } else {
                                echo '🌐 URL';
                            }
                            ?>
                        </span>
                    </div>
                    
                    <div class="property-actions">
                        <a href="../property.php?id=<?php echo $property['id']; ?>" class="btn btn-secondary" target="_blank">View</a>
                        <a href="edit_property.php?id=<?php echo $property['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="properties.php?delete=<?php echo $property['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">Delete</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if(empty($properties)): ?>
            <div class="empty-state">
                <h3>No Properties Found</h3>
                <p>You haven't added any properties yet.</p>
                <a href="add_property.php" class="btn btn-primary">Add Your First Property</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
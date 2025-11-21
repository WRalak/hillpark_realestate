<?php 
include 'config.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$property_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$property_id]);
    $property = $stmt->fetch();
    
    if(!$property) {
        header("Location: index.php");
        exit();
    }
} catch(Exception $e) {
    die("Error loading property: " . $e->getMessage());
}

include 'includes/header.php'; 

// Use the getImageUrl function to properly handle uploaded images
$image_url = getImageUrl($property['image_url']);
?>

<div class="container">
    <div class="property-detail">
        <div class="property-gallery">
            <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="property-main-image" onerror="this.src='<?php echo DEFAULT_PROPERTY_IMAGE; ?>'">
        </div>
        
        <div class="property-content">
            <h1><?php echo htmlspecialchars($property['title']); ?></h1>
            <p class="price">$<?php echo number_format($property['price']); ?></p>
            <p class="location">📍 <?php echo htmlspecialchars($property['location']); ?></p>
            
            <div class="property-features">
                <div class="feature">
                    <strong>🛏️ Bedrooms:</strong> <?php echo $property['bedrooms']; ?>
                </div>
                <div class="feature">
                    <strong>🚿 Bathrooms:</strong> <?php echo $property['bathrooms']; ?>
                </div>
                <div class="feature">
                    <strong>📐 Area:</strong> <?php echo number_format($property['area']); ?> sqft
                </div>
                <div class="feature">
                    <strong>🏠 Type:</strong> <?php echo ucfirst($property['type']); ?>
                </div>
                <div class="feature">
                    <strong>📊 Status:</strong> 
                    <span class="status-badge status-<?php echo $property['status']; ?>">
                        <?php echo ucfirst($property['status']); ?>
                    </span>
                </div>
                <?php if($property['featured']): ?>
                <div class="feature">
                    <strong>⭐ Featured:</strong> Yes
                </div>
                <?php endif; ?>
            </div>
            
            <div class="property-description">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
            </div>
            
            <div class="contact-form">
                <h3>Schedule a Viewing</h3>
                
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert success">
                        ✅ Thank you! Your inquiry has been sent successfully.
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert error">
                        ❌ There was an error sending your inquiry. Please try again.
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="submit_inquiry.php">
                    <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                    
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Your Phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <textarea name="message" placeholder="Your Message" required><?php echo htmlspecialchars($_POST['message'] ?? "I'm interested in " . $property['title'] . ". Please contact me with more information."); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Inquiry</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
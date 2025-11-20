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

$image_url = !empty($property['image_url']) ? $property['image_url'] : DEFAULT_PROPERTY_IMAGE;
?>

<div class="container">
    <div class="property-detail">
        <div class="property-gallery">
            <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="property-main-image">
        </div>
        
        <div class="property-content">
            <h1><?php echo htmlspecialchars($property['title']); ?></h1>
            <p class="price">$<?php echo number_format($property['price']); ?></p>
            <p class="location"><?php echo htmlspecialchars($property['location']); ?></p>
            
            <div class="property-features">
                <div class="feature">
                    <strong>Bedrooms:</strong> <?php echo $property['bedrooms']; ?>
                </div>
                <div class="feature">
                    <strong>Bathrooms:</strong> <?php echo $property['bathrooms']; ?>
                </div>
                <div class="feature">
                    <strong>Area:</strong> <?php echo number_format($property['area']); ?> sqft
                </div>
                <div class="feature">
                    <strong>Type:</strong> <?php echo ucfirst($property['type']); ?>
                </div>
                <div class="feature">
                    <strong>Status:</strong> <?php echo ucfirst($property['status']); ?>
                </div>
            </div>
            
            <div class="property-description">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
            </div>
            
            <div class="contact-form">
                <h3>Schedule a Viewing</h3>
                <form method="POST" action="submit_inquiry.php">
                    <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="tel" name="phone" placeholder="Your Phone">
                    <textarea name="message" placeholder="Your Message" required></textarea>
                    <button type="submit" class="btn btn-primary">Send Inquiry</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
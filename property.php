<?php 
include 'config.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$property_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->execute([$property_id]);
$property = $stmt->fetch();

if(!$property) {
    header("Location: index.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="property-detail">
        <div class="property-gallery">
            <img src="<?php echo $property['image_url'] ?: 'assets/images/default-property.jpg'; ?>" alt="<?php echo $property['title']; ?>">
        </div>
        
        <div class="property-content">
            <h1><?php echo $property['title']; ?></h1>
            <p class="price">$<?php echo number_format($property['price']); ?></p>
            <p class="location"><?php echo $property['location']; ?></p>
            
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
                    <strong>Type:</strong> <?php echo $property['type']; ?>
                </div>
            </div>
            
            <div class="property-description">
                <h3>Description</h3>
                <p><?php echo $property['description']; ?></p>
            </div>
            
            <div class="contact-form">
                <h3>Schedule a Viewing</h3>
                <form method="POST">
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
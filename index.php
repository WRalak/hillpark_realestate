<?php include 'config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="hero">
    <div class="hero-content">
        <h1>Find Your Dream Home</h1>
        <p>Discover the perfect property in Hillpark's most desirable neighborhoods</p>
        <a href="search.php" class="btn btn-primary">Browse Properties</a>
    </div>
</div>

<div class="container">
    <section class="featured-properties">
        <h2>Featured Properties</h2>
        <div class="properties-grid">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM properties WHERE featured = 1 AND status = 'available' LIMIT 6");
                $stmt->execute();
                $properties = $stmt->fetchAll();
                
                if(count($properties) > 0) {
                    foreach($properties as $property):
            ?>
            <div class="property-card">
                <img src="<?php echo !empty($property['image_url']) ? htmlspecialchars($property['image_url']) : 'assets/images/default-property.jpg'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                <div class="property-info">
                    <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                    <p class="price">$<?php echo number_format($property['price']); ?></p>
                    <p class="location"><?php echo htmlspecialchars($property['location']); ?></p>
                    <div class="property-features">
                        <span><?php echo $property['bedrooms']; ?> Beds</span>
                        <span><?php echo $property['bathrooms']; ?> Baths</span>
                        <span><?php echo number_format($property['area']); ?> sqft</span>
                    </div>
                    <a href="property.php?id=<?php echo $property['id']; ?>" class="btn btn-secondary">View Details</a>
                </div>
            </div>
            <?php 
                    endforeach;
                } else {
                    echo '<p>No featured properties available at the moment.</p>';
                }
            } catch(Exception $e) {
                echo '<p>Error loading featured properties. Please try again later.</p>';
            }
            ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
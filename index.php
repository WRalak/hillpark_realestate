<?php include 'config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo HERO_IMAGE; ?>');">
    <div class="hero-content">
        <h1>Find Your Dream Home in Hillpark</h1>
        <p>Discover exclusive properties in the most desirable neighborhoods. Luxury living awaits.</p>
        <div class="hero-buttons">
            <a href="search.php" class="btn btn-primary">Browse Properties</a>
            <a href="search.php?featured=1" class="btn btn-secondary">View Featured Homes</a>
        </div>
    </div>
</div>

<div class="container">
    <section class="featured-properties">
        <h2>Featured Properties</h2>
        <p class="section-subtitle">Handpicked selections of our finest homes</p>
        <div class="properties-grid">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM properties WHERE featured = 1 AND status = 'available' ORDER BY price DESC LIMIT 9");
                $stmt->execute();
                $properties = $stmt->fetchAll();
                
                if(count($properties) > 0) {
                    foreach($properties as $property):
                    $image_url = !empty($property['image_url']) ? $property['image_url'] : DEFAULT_PROPERTY_IMAGE;
            ?>
            <div class="property-card">
                <div class="property-image-container">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="property-image">
                    <?php if($property['featured']): ?>
                        <span class="featured-badge">Featured</span>
                    <?php endif; ?>
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
                    <div class="property-type">
                        <span class="type-badge type-<?php echo $property['type']; ?>">
                            <?php echo ucfirst($property['type']); ?>
                        </span>
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
        <div class="view-all-container">
            <a href="search.php" class="btn btn-primary">View All Properties</a>
        </div>
    </section>

    <section class="property-types">
        <h2>Browse By Property Type</h2>
        <div class="types-grid">
            <div class="type-card">
                <div class="type-icon">🏠</div>
                <h3>Houses</h3>
                <p>Single family homes with yards and privacy</p>
                <a href="search.php?type=house" class="btn btn-outline">Browse Houses</a>
            </div>
            <div class="type-card">
                <div class="type-icon">🏢</div>
                <h3>Apartments</h3>
                <p>Modern apartments in great locations</p>
                <a href="search.php?type=apartment" class="btn btn-outline">Browse Apartments</a>
            </div>
            <div class="type-card">
                <div class="type-icon">🏬</div>
                <h3>Condos</h3>
                <p>Maintenance-free condominium living</p>
                <a href="search.php?type=condo" class="btn btn-outline">Browse Condos</a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
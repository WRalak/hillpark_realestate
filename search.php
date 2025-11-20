<?php 
include 'config.php';
include 'includes/header.php'; 
?>

<div class="container">
    <h1>Find Your Perfect Property</h1>
    
    <div class="search-filters">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
            </div>
            <div class="filter-group">
                <select name="type">
                    <option value="">Property Type</option>
                    <option value="house" <?php echo ($_GET['type'] ?? '') == 'house' ? 'selected' : ''; ?>>House</option>
                    <option value="apartment" <?php echo ($_GET['type'] ?? '') == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                    <option value="condo" <?php echo ($_GET['type'] ?? '') == 'condo' ? 'selected' : ''; ?>>Condo</option>
                </select>
            </div>
            <div class="filter-group">
                <input type="number" name="min_price" placeholder="Min Price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
            </div>
            <div class="filter-group">
                <input type="number" name="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
            </div>
            <div class="filter-group">
                <select name="bedrooms">
                    <option value="">Bedrooms</option>
                    <option value="1" <?php echo ($_GET['bedrooms'] ?? '') == '1' ? 'selected' : ''; ?>>1+</option>
                    <option value="2" <?php echo ($_GET['bedrooms'] ?? '') == '2' ? 'selected' : ''; ?>>2+</option>
                    <option value="3" <?php echo ($_GET['bedrooms'] ?? '') == '3' ? 'selected' : ''; ?>>3+</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    
    <div class="search-results">
        <h2>Available Properties</h2>
        <div class="properties-grid">
            <?php
            try {
                $sql = "SELECT * FROM properties WHERE status = 'available'";
                $params = [];
                
                if(isset($_GET['location']) && !empty($_GET['location'])) {
                    $sql .= " AND location LIKE ?";
                    $params[] = '%' . $_GET['location'] . '%';
                }
                
                if(isset($_GET['type']) && !empty($_GET['type'])) {
                    $sql .= " AND type = ?";
                    $params[] = $_GET['type'];
                }
                
                if(isset($_GET['min_price']) && !empty($_GET['min_price'])) {
                    $sql .= " AND price >= ?";
                    $params[] = $_GET['min_price'];
                }
                
                if(isset($_GET['max_price']) && !empty($_GET['max_price'])) {
                    $sql .= " AND price <= ?";
                    $params[] = $_GET['max_price'];
                }
                
                if(isset($_GET['bedrooms']) && !empty($_GET['bedrooms'])) {
                    $sql .= " AND bedrooms >= ?";
                    $params[] = $_GET['bedrooms'];
                }
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $properties = $stmt->fetchAll();
                
                if(count($properties) > 0) {
                    foreach($properties as $property):
                    $image_url = !empty($property['image_url']) ? $property['image_url'] : DEFAULT_PROPERTY_IMAGE;
            ?>
            <div class="property-card">
                <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="property-image">
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
                    echo '<p>No properties found matching your criteria.</p>';
                }
            } catch(Exception $e) {
                echo '<p>Error loading properties. Please try again later.</p>';
            }
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
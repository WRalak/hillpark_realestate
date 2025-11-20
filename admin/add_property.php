<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add_property'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $area = $_POST['area'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $image_url = $_POST['image_url'] ?: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop';
    
    $stmt = $pdo->prepare("INSERT INTO properties (title, description, price, location, bedrooms, bathrooms, area, type, status, featured, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if($stmt->execute([$title, $description, $price, $location, $bedrooms, $bathrooms, $area, $type, $status, $featured, $image_url])) {
        $success = "Property added successfully!";
    } else {
        $error = "Error adding property.";
    }
}
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Add New Property</h1>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="property-form">
            <div class="form-group">
                <label>Property Title *</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Price *</label>
                    <input type="number" name="price" required>
                </div>
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Bedrooms *</label>
                    <input type="number" name="bedrooms" required>
                </div>
                
                <div class="form-group">
                    <label>Bathrooms *</label>
                    <input type="number" name="bathrooms" step="0.5" required>
                </div>
                
                <div class="form-group">
                    <label>Area (sqft) *</label>
                    <input type="number" name="area" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Property Type *</label>
                    <select name="type" required>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                        <option value="condo">Condo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="available">Available</option>
                        <option value="sold">Sold</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Image URL</label>
                <input type="url" name="image_url" placeholder="https://example.com/image.jpg">
                <small>Leave empty for default image</small>
            </div>
            
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="featured"> 
                    Featured Property (shows on homepage)
                </label>
            </div>
            
            <button type="submit" name="add_property" class="btn">Add Property</button>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
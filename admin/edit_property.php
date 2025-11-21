<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get property data
$property_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = null;

if($property_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
        $stmt->execute([$property_id]);
        $property = $stmt->fetch();
        
        if(!$property) {
            header("Location: properties.php");
            exit();
        }
    } catch(Exception $e) {
        die("Error loading property: " . $e->getMessage());
    }
} else {
    header("Location: properties.php");
    exit();
}

$success = $error = '';

if(isset($_POST['update_property'])) {
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
    $image_url = $_POST['image_url'] ?? '';
    
    // Handle file upload
    $uploaded_image = '';
    if(isset($_FILES['property_image']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['property_image'];
        
        // Check file size (5MB max)
        if($file['size'] > 5242880) {
            $error = 'File is too large. Maximum size is 5MB.';
        }
        // Check file type
        elseif(!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $error = 'Only JPG, JPEG, PNG, GIF, and WebP files are allowed.';
        }
        // Upload file
        else {
            // Create uploads directory if it doesn't exist
            $upload_dir = '../uploads/';
            if(!is_dir($upload_dir)) {
                if(mkdir($upload_dir, 0755, true)) {
                    echo "<!-- Debug: Created uploads directory -->";
                } else {
                    $error = 'Cannot create uploads directory. Check folder permissions.';
                }
            }
            
            if(empty($error)) {
                // Generate unique filename
                $filename = uniqid() . '_' . time() . '.' . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_path = $upload_dir . $filename;
                $relative_path = 'uploads/' . $filename;
                
                if(move_uploaded_file($file['tmp_name'], $file_path)) {
                    $uploaded_image = $relative_path;
                } else {
                    $error = 'Failed to upload file. Check folder permissions.';
                }
            }
        }
    }
    
    // Use uploaded image, then provided URL, then keep existing image
    $final_image = $property['image_url']; // Keep existing by default
    
    if(!empty($uploaded_image)) {
        $final_image = $uploaded_image;
    } elseif(!empty($image_url)) {
        $final_image = $image_url;
    }
    // If both are empty, keep the existing image
    
    if(empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE properties SET title = ?, description = ?, price = ?, location = ?, bedrooms = ?, bathrooms = ?, area = ?, type = ?, status = ?, featured = ?, image_url = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            
            if($stmt->execute([$title, $description, $price, $location, $bedrooms, $bathrooms, $area, $type, $status, $featured, $final_image, $property_id])) {
                $success = "Property updated successfully!";
                
                // Update the current property data for display
                $property['title'] = $title;
                $property['description'] = $description;
                $property['price'] = $price;
                $property['location'] = $location;
                $property['bedrooms'] = $bedrooms;
                $property['bathrooms'] = $bathrooms;
                $property['area'] = $area;
                $property['type'] = $type;
                $property['status'] = $status;
                $property['featured'] = $featured;
                $property['image_url'] = $final_image;
            } else {
                $error = "Error updating property in database.";
            }
        } catch(Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Edit Property</h1>
        
        <?php if($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="property-form">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            
            <div class="form-group">
                <label>Property Title *</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($property['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="4" required><?php echo htmlspecialchars($property['description']); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Price ($) *</label>
                    <input type="number" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Bedrooms *</label>
                    <input type="number" name="bedrooms" value="<?php echo htmlspecialchars($property['bedrooms']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Bathrooms *</label>
                    <input type="number" name="bathrooms" step="0.5" value="<?php echo htmlspecialchars($property['bathrooms']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Area (sqft) *</label>
                    <input type="number" name="area" value="<?php echo htmlspecialchars($property['area']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Property Type *</label>
                    <select name="type" required>
                        <option value="house" <?php echo $property['type'] == 'house' ? 'selected' : ''; ?>>House</option>
                        <option value="apartment" <?php echo $property['type'] == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                        <option value="condo" <?php echo $property['type'] == 'condo' ? 'selected' : ''; ?>>Condo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="available" <?php echo $property['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="sold" <?php echo $property['status'] == 'sold' ? 'selected' : ''; ?>>Sold</option>
                        <option value="pending" <?php echo $property['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Current Image</label>
                <div class="current-image">
                    <img src="<?php echo getImageUrl($property['image_url']); ?>" alt="Current property image" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                    <p><small>Current image: <?php echo $property['image_url']; ?></small></p>
                </div>
            </div>
            
            <div class="form-group">
                <label>Upload New Property Image</label>
                <input type="file" name="property_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                <small>Max file size: 5MB. Allowed types: JPG, JPEG, PNG, GIF, WebP</small>
            </div>
            
            <div class="form-group">
                <label>Or Use New Image URL</label>
                <input type="url" name="image_url" value="" placeholder="https://example.com/image.jpg">
                <small>Leave empty to keep current image</small>
            </div>
            
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="featured" <?php echo $property['featured'] ? 'checked' : ''; ?>> 
                    Featured Property (shows on homepage)
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="update_property" class="btn btn-primary">Update Property</button>
                <a href="properties.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
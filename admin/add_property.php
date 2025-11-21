<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = $error = '';

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
                $relative_path = 'uploads/' . $filename; // Relative path for database
                
                echo "<!-- Debug: Uploading to: $file_path -->";
                echo "<!-- Debug: Relative path: $relative_path -->";
                
                if(move_uploaded_file($file['tmp_name'], $file_path)) {
                    $uploaded_image = $relative_path; // Store relative path for database
                    
                    // Verify file was actually saved
                    if(file_exists($file_path)) {
                        echo "<!-- Debug: File uploaded successfully. File exists: yes -->";
                        echo "<!-- Debug: File size: " . filesize($file_path) . " -->";
                    } else {
                        $error = 'File upload failed - file not found after upload.';
                        echo "<!-- Debug: File upload failed - file not found -->";
                    }
                } else {
                    $error = 'Failed to upload file. Check folder permissions.';
                    echo "<!-- Debug: move_uploaded_file() failed -->";
                    // Get the actual error
                    $upload_error = error_get_last();
                    if($upload_error) {
                        echo "<!-- Debug: Error: " . $upload_error['message'] . " -->";
                    }
                }
            }
        }
    } elseif(isset($_FILES['property_image']) && $_FILES['property_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // There was an upload error
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
            UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
            UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'PHP extension stopped the file upload'
        ];
        $error_code = $_FILES['property_image']['error'];
        $error = 'Upload error: ' . ($upload_errors[$error_code] ?? 'Unknown error');
    }
    
    // Use uploaded image, then URL, then default
    $final_image = '';
    if(!empty($uploaded_image)) {
        $final_image = $uploaded_image;
        echo "<!-- Debug: Using uploaded image: $uploaded_image -->";
    } elseif(!empty($image_url)) {
        $final_image = $image_url;
        echo "<!-- Debug: Using image URL: $image_url -->";
    } else {
        $final_image = DEFAULT_PROPERTY_IMAGE;
        echo "<!-- Debug: Using default image -->";
    }
    
    if(empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO properties (title, description, price, location, bedrooms, bathrooms, area, type, status, featured, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if($stmt->execute([$title, $description, $price, $location, $bedrooms, $bathrooms, $area, $type, $status, $featured, $final_image])) {
            $success = "Property added successfully!";
            
            // Verify the image can be accessed
            if(!empty($uploaded_image)) {
                $test_url = getImageUrl($uploaded_image);
                echo "<!-- Debug: Image should be accessible at: $test_url -->";
            }
            
            // Clear form
            $_POST = array();
        } else {
            $error = "Error adding property to database.";
        }
    }
}
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <h1>Add New Property</h1>
        
        <?php if($success): ?>
            <div class="alert success">
                <?php echo $success; ?>
                <?php if(isset($test_url)): ?>
                    <br><small>Image URL: <?php echo $test_url; ?></small>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert error">
                <?php echo $error; ?>
                <br><small>Check the uploads folder exists and has write permissions.</small>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="property-form">
            <div class="form-group">
                <label>Property Title *</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Price ($) *</label>
                    <input type="number" name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Bedrooms *</label>
                    <input type="number" name="bedrooms" value="<?php echo htmlspecialchars($_POST['bedrooms'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Bathrooms *</label>
                    <input type="number" name="bathrooms" step="0.5" value="<?php echo htmlspecialchars($_POST['bathrooms'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Area (sqft) *</label>
                    <input type="number" name="area" value="<?php echo htmlspecialchars($_POST['area'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Property Type *</label>
                    <select name="type" required>
                        <option value="house" <?php echo ($_POST['type'] ?? '') == 'house' ? 'selected' : ''; ?>>House</option>
                        <option value="apartment" <?php echo ($_POST['type'] ?? '') == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                        <option value="condo" <?php echo ($_POST['type'] ?? '') == 'condo' ? 'selected' : ''; ?>>Condo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="available" <?php echo ($_POST['status'] ?? '') == 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="sold" <?php echo ($_POST['status'] ?? '') == 'sold' ? 'selected' : ''; ?>>Sold</option>
                        <option value="pending" <?php echo ($_POST['status'] ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Upload Property Image</label>
                <input type="file" name="property_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                <small>Max file size: 5MB. Allowed types: JPG, JPEG, PNG, GIF, WebP</small>
            </div>
            
            <div class="form-group">
                <label>Or Use Image URL</label>
                <input type="url" name="image_url" value="<?php echo htmlspecialchars($_POST['image_url'] ?? ''); ?>" placeholder="https://example.com/image.jpg">
                <small>Leave both empty for default image</small>
            </div>
            
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="featured" <?php echo isset($_POST['featured']) ? 'checked' : ''; ?>> 
                    Featured Property (shows on homepage)
                </label>
            </div>
            
            <button type="submit" name="add_property" class="btn btn-primary">Add Property</button>
        </form>
        
        <div class="upload-help" style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 5px;">
            <h3>Upload Troubleshooting</h3>
            <p>If uploads fail, check:</p>
            <ul>
                <li>The <code>uploads/</code> folder exists in your project root</li>
                <li>The folder has write permissions (755 or 777)</li>
                <li>File size is under 5MB</li>
                <li>File type is JPG, PNG, GIF, or WebP</li>
            </ul>
            <p><a href="../debug_upload.php" target="_blank">Check Upload Status</a></p>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
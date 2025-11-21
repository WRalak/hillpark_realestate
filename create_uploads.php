<?php
// Create uploads directory
if (!is_dir('uploads')) {
    if (mkdir('uploads', 0755, true)) {
        echo "Uploads directory created successfully!";
    } else {
        echo "Failed to create uploads directory.";
    }
} else {
    echo "Uploads directory already exists.";
}

// Check permissions
echo "<br>Uploads directory permissions: " . substr(sprintf('%o', fileperms('uploads')), -4);
?>
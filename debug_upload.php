<?php
include 'config.php';

echo "<h2>Debugging Upload Process</h2>";

// Check uploads directory
echo "<h3>Uploads Directory Check</h3>";
$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    echo "<p style='color: red;'>❌ Uploads directory doesn't exist</p>";
} else {
    echo "<p style='color: green;'>✅ Uploads directory exists</p>";
    
    // List all files in uploads
    $files = scandir($upload_dir);
    echo "<p>Files in uploads directory:</p>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $file_path = $upload_dir . $file;
            $file_url = getImageUrl($file_path);
            $file_size = filesize($file_path);
            $file_time = date('Y-m-d H:i:s', filemtime($file_path));
            echo "<li>";
            echo "<strong>$file</strong> (Size: $file_size bytes, Modified: $file_time)";
            echo "<br><img src='$file_url' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'>";
            echo "</li>";
        }
    }
    echo "</ul>";
}

// Check database images
echo "<h3>Database Images Check</h3>";
try {
    $stmt = $pdo->query("SELECT id, title, image_url, created_at FROM properties ORDER BY created_at DESC");
    $properties = $stmt->fetchAll();
    
    foreach($properties as $property) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<h4>" . $property['title'] . " (ID: " . $property['id'] . ")</h4>";
        echo "<p>Stored image_url: <code>" . $property['image_url'] . "</code></p>";
        echo "<p>Processed URL: <code>" . getImageUrl($property['image_url']) . "</code></p>";
        
        $final_url = getImageUrl($property['image_url']);
        echo "<img src='$final_url' style='max-width: 300px; max-height: 200px; border: 2px solid blue;'>";
        
        // Check if file exists for local paths
        if (strpos($property['image_url'], 'uploads/') === 0) {
            if (file_exists($property['image_url'])) {
                echo "<p style='color: green;'>✅ Local file exists</p>";
            } else {
                echo "<p style='color: red;'>❌ Local file NOT found: " . $property['image_url'] . "</p>";
            }
        }
        
        echo "</div>";
    }
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
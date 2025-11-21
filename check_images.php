<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

echo "<h2>Checking Property Images</h2>";

try {
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        echo "<p style='color: red;'>Error: Database connection not initialized or not a PDO instance.</p>";
        $properties = [];
    } else {
        $stmt = $pdo->query("SELECT id, title, image_url FROM properties");
        $properties = $stmt->fetchAll();
    }
    
    foreach($properties as $property) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<h3>" . htmlspecialchars($property['title']) . " (ID: " . $property['id'] . ")</h3>";
        echo "<p>Image URL: " . $property['image_url'] . "</p>";
        
        // Check if image exists
        if (!empty($property['image_url'])) {
            if (strpos($property['image_url'], 'http') === 0) {
                // External URL
                echo "<p>Type: External URL</p>";
                echo "<img src='" . $property['image_url'] . "' style='max-width: 300px; max-height: 200px; border: 1px solid green;'>";
            } else {
                // Local file
                echo "<p>Type: Local file</p>";
                echo "<p>File exists: " . (file_exists($property['image_url']) ? '✅ Yes' : '❌ No') . "</p>";
                if (file_exists($property['image_url'])) {
                    echo "<img src='" . $property['image_url'] . "' style='max-width: 300px; max-height: 200px; border: 1px solid green;'>";
                } else {
                    echo "<p style='color: red;'>File not found: " . $property['image_url'] . "</p>";
                }
            }
        } else {
            echo "<p style='color: orange;'>No image URL set</p>";
        }
        
        echo "</div>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
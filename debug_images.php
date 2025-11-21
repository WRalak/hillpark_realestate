<?php
include 'config.php';

echo "<h2>Debugging Image URLs</h2>";

try {
    $stmt = $pdo->query("SELECT id, title, image_url FROM properties");
    $properties = $stmt->fetchAll();
    
    foreach($properties as $property) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<h3>" . $property['title'] . " (ID: " . $property['id'] . ")</h3>";
        echo "<p>Stored URL: " . $property['image_url'] . "</p>";
        echo "<p>Processed URL: " . getImageUrl($property['image_url']) . "</p>";
        
        $final_url = getImageUrl($property['image_url']);
        echo "<img src='$final_url' style='max-width: 300px; max-height: 200px; border: 2px solid green;'>";
        
        echo "</div>";
    }
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Testing Hillpark Real Estate</h1>";

// Test database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=hillpark_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    
    // Test if properties table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM properties");
    $result = $stmt->fetch();
    echo "<p>Properties in database: " . $result['count'] . "</p>";
    
    // Show some properties
    $stmt = $pdo->query("SELECT * FROM properties LIMIT 3");
    $properties = $stmt->fetchAll();
    
    foreach($properties as $property) {
        echo "<p>" . $property['title'] . " - $" . number_format($property['price']) . "</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test if files exist
$files = ['config.php', 'includes/header.php', 'includes/footer.php'];
foreach($files as $file) {
    if(file_exists($file)) {
        echo "<p style='color: green;'>✅ $file exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file is missing</p>";
    }
}
?>
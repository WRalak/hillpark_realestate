<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Simple Database Setup</h2>";

// Try the most common working connection for XAMPP
try {
    $pdo = new PDO("mysql:host=127.0.0.1", "root", "");
    echo "<p style='color: green;'>✅ Connected to MySQL</p>";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS hillpark_db");
    echo "<p style='color: green;'>✅ Database created</p>";
    
    // Use the database
    $pdo->exec("USE hillpark_db");
    
    // Simple table creation
    $pdo->exec("CREATE TABLE IF NOT EXISTS properties (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        price DECIMAL(12,2) NOT NULL,
        location VARCHAR(255) NOT NULL,
        bedrooms INT NOT NULL,
        image_url VARCHAR(500)
    )");
    
    echo "<p style='color: green;'>✅ Properties table created</p>";
    
    // Add a sample property
    $pdo->exec("INSERT IGNORE INTO properties (title, price, location, bedrooms, image_url) VALUES 
        ('Sample Home', 350000, 'Hillpark', 3, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop')");
    
    echo "<p style='color: green;'>✅ Sample property added</p>";
    echo "<h3 style='color: green;'>🎉 Setup complete! <a href='index.php'>View Website</a></h3>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Solution:</strong></p>";
    echo "<ol>";
    echo "<li>Open XAMPP Control Panel</li>";
    echo "<li>Make sure MySQL is running (green 'Stop' button)</li>";
    echo "<li>If MySQL won't start, check the logs in C:\\xampp\\mysql\\data\\</li>";
    echo "<li>Try restarting XAMPP completely</li>";
    echo "</ol>";
}
?>
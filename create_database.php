<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Creating Hillpark Database</h2>";

try {
    // Connect to MySQL (without specifying database)
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Connected to MySQL successfully</p>";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS hillpark_db");
    echo "<p style='color: green;'>✅ Database 'hillpark_db' created</p>";
    
    // Use the database
    $pdo->exec("USE hillpark_db");
    
    // Create tables
    $tables = [
        "users" => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'agent') DEFAULT 'agent',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "properties" => "CREATE TABLE IF NOT EXISTS properties (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(12,2) NOT NULL,
            location VARCHAR(255) NOT NULL,
            bedrooms INT NOT NULL,
            bathrooms DECIMAL(3,1) NOT NULL,
            area INT NOT NULL,
            type ENUM('house', 'apartment', 'condo') NOT NULL,
            status ENUM('available', 'sold', 'pending') DEFAULT 'available',
            featured BOOLEAN DEFAULT FALSE,
            image_url VARCHAR(500),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "inquiries" => "CREATE TABLE IF NOT EXISTS inquiries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            property_id INT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL
        )"
    ];
    
    foreach($tables as $name => $sql) {
        $pdo->exec($sql);
        echo "<p style='color: green;'>✅ Table '$name' created</p>";
    }
    
    // Insert admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO users (username, password, role) VALUES 
               ('admin', '$admin_password', 'admin')");
    echo "<p style='color: green;'>✅ Admin user created (username: admin, password: admin123)</p>";
    
    // Insert sample properties
    $sample_properties = [
        "('Modern Downtown Apartment', 'Beautiful modern apartment in the heart of downtown with stunning city views. Features include hardwood floors, stainless steel appliances, and a spacious balcony.', 350000, 'Downtown Hillpark', 2, 2, 1200, 'apartment', 'available', 1, 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&h=400&fit=crop')",
        "('Luxury Family Home', 'Spacious family home with large backyard and modern amenities. Perfect for growing families with 4 bedrooms and 3 bathrooms.', 750000, 'Hillpark Suburbs', 4, 3, 2500, 'house', 'available', 1, 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&h=400&fit=crop')",
        "('Waterfront Condo', 'Luxurious waterfront condo with balcony and pool access. Enjoy stunning lake views from every room.', 450000, 'Lakeview District', 2, 2, 1100, 'condo', 'available', 1, 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=600&h=400&fit=crop')",
        "('Cozy Starter Home', 'Perfect starter home in quiet neighborhood with recent renovations. Move-in ready with updated kitchen and bathroom.', 275000, 'North Hillpark', 3, 2, 1500, 'house', 'available', 0, 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=600&h=400&fit=crop')",
        "('Luxury Penthouse Suite', 'Stunning penthouse with panoramic views and premium finishes. Features high ceilings and premium appliances.', 1200000, 'Downtown Hillpark', 3, 3, 2200, 'apartment', 'available', 1, 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop')"
    ];
    
    foreach($sample_properties as $property) {
        $pdo->exec("INSERT IGNORE INTO properties (title, description, price, location, bedrooms, bathrooms, area, type, status, featured, image_url) VALUES $property");
    }
    echo "<p style='color: green;'>✅ 5 sample properties added</p>";
    
    echo "<h3 style='color: green;'>🎉 Database setup complete!</h3>";
    echo "<p><a href='index.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Website</a></p>";
    echo "<p><a href='login.php'>Admin Login</a> (admin / admin123)</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
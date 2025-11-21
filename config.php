<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hillpark_db');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Site configuration
define('SITE_NAME', 'Hillpark Real Estate');
define('SITE_URL', 'http://localhost/hillpark_realestate');
define('DEFAULT_PROPERTY_IMAGE', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop');

// Image helper function
function getImageUrl($image_url) {
    if (empty($image_url)) {
        return DEFAULT_PROPERTY_IMAGE;
    }
    
    // If it's already a full URL, return as is
    if (strpos($image_url, 'http') === 0) {
        return $image_url;
    }
    
    // If it's a local file path, make it a web URL
    if (strpos($image_url, 'uploads/') === 0) {
        return SITE_URL . '/' . $image_url;
    }
    
    // Default fallback
    return DEFAULT_PROPERTY_IMAGE;
}
?>
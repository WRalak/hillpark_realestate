<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hillpark_db');
define('DB_PORT', 3306);

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT.";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Site configuration
define('SITE_NAME', 'Hillpark Real Estate');
define('SITE_URL', 'http://localhost/hillpark_realestate');
define('HERO_IMAGE', 'https://images.unsplash.com/photo-1513584684374-8bab748fbf90?w=1200&h=600&fit=crop');
define('DEFAULT_PROPERTY_IMAGE', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=600&h=400&fit=crop');
?>
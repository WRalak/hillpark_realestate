<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Fixing Uploads Directory</h2>";

$upload_dir = 'uploads/';

// Check if directory exists
if (!is_dir($upload_dir)) {
    echo "<p>Creating uploads directory...</p>";
    if (mkdir($upload_dir, 0755, true)) {
        echo "<p style='color: green;'>✅ Uploads directory created</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create uploads directory</p>";
        echo "<p>Try creating the folder manually: <code>mkdir uploads</code></p>";
    }
} else {
    echo "<p style='color: green;'>✅ Uploads directory exists</p>";
}

// Check permissions
if (is_writable($upload_dir)) {
    echo "<p style='color: green;'>✅ Uploads directory is writable</p>";
} else {
    echo "<p style='color: red;'>❌ Uploads directory is NOT writable</p>";
    echo "<p>Try running: <code>chmod 755 uploads/</code> or <code>chmod 777 uploads/</code></p>";
}

// Test creating a file
$test_file = $upload_dir . 'test_permissions.txt';
if (file_put_contents($test_file, 'Test file created at ' . date('Y-m-d H:i:s'))) {
    echo "<p style='color: green;'>✅ Can create files in uploads directory</p>";
    unlink($test_file); // Clean up
} else {
    echo "<p style='color: red;'>❌ Cannot create files in uploads directory</p>";
}

echo "<p><a href='debug_upload.php'>Check Upload Results Again</a></p>";
?>
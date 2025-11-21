<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Checking Uploads Directory</h2>";

$upload_dir = 'uploads/';

// Check if directory exists
if (!is_dir($upload_dir)) {
    echo "<p style='color: orange;'>Uploads directory doesn't exist. Creating it...</p>";
    if (mkdir($upload_dir, 0755, true)) {
        echo "<p style='color: green;'>✅ Uploads directory created</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create uploads directory</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Uploads directory exists</p>";
}

// Check permissions
if (is_writable($upload_dir)) {
    echo "<p style='color: green;'>✅ Uploads directory is writable</p>";
} else {
    echo "<p style='color: red;'>❌ Uploads directory is NOT writable</p>";
    echo "<p>Try running: <code>chmod 755 uploads/</code></p>";
}

// List files in uploads directory
echo "<h3>Files in uploads directory:</h3>";
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    if (count($files) > 2) {
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $file_path = $upload_dir . $file;
                $file_url = 'http://localhost/hillpark_realestate/' . $file_path;
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px;'>";
                echo "<p>File: $file</p>";
                echo "<p>Path: $file_path</p>";
                echo "<p>URL: <a href='$file_url' target='_blank'>$file_url</a></p>";
                echo "<img src='$file_url' style='max-width: 200px; max-height: 150px;'>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>No files in uploads directory</p>";
    }
}
?>
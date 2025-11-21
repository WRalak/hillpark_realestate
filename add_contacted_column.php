<?php
include 'config.php';

echo "<h2>Adding Contacted Column to Inquiries Table</h2>";

try {
    $pdo->exec("ALTER TABLE inquiries ADD COLUMN contacted BOOLEAN DEFAULT FALSE");
    echo "<p style='color: green;'>✅ Successfully added 'contacted' column to inquiries table</p>";
    
    // Show table structure
    $stmt = $pdo->query("DESCRIBE inquiries");
    $columns = $stmt->fetchAll();
    
    echo "<h3>Current Inquiries Table Structure:</h3>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
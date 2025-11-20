<?php
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle property deletion
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM properties WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: properties.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
$properties = $stmt->fetchAll();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-container">
    <?php include 'includes/admin_sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="page-header">
            <h1>Manage Properties</h1>
            <a href="add_property.php" class="btn btn-primary">Add New Property</a>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($properties as $property): ?>
                <tr>
                    <td><?php echo $property['title']; ?></td>
                    <td>$<?php echo number_format($property['price']); ?></td>
                    <td><?php echo $property['location']; ?></td>
                    <td><?php echo ucfirst($property['type']); ?></td>
                    <td><?php echo ucfirst($property['status']); ?></td>
                    <td class="actions">
                        <a href="../property.php?id=<?php echo $property['id']; ?>" class="btn btn-secondary">View</a>
                        <a href="edit_property.php?id=<?php echo $property['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="properties.php?delete=<?php echo $property['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="admin-nav">
            <h2><?php echo SITE_NAME; ?> Admin</h2>
            <div class="admin-user">
                Welcome, <?php echo $_SESSION['username']; ?> | 
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </header>
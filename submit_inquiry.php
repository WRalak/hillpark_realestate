<?php
include 'config.php';

if($_POST) {
    $property_id = (int)$_POST['property_id'];
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $message = htmlspecialchars($_POST['message']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO inquiries (property_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$property_id, $name, $email, $phone, $message]);
        
        header("Location: property.php?id=$property_id&success=1");
        exit();
    } catch(Exception $e) {
        header("Location: property.php?id=$property_id&error=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<?php
require_once 'backend/php/db_config.php';
$stmt = $pdo->query("SELECT id, name, image_url FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($categories, JSON_PRETTY_PRINT);
?>
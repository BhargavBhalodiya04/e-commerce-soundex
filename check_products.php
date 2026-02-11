<?php
require_once 'backend/php/db_config.php';
$stmt = $pdo->query("SELECT id, name, image_url FROM products LIMIT 10");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products, JSON_PRETTY_PRINT);
?>
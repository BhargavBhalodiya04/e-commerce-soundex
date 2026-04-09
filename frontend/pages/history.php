<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/OrderManager.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$orderManager = new OrderManager($pdo);
$orders = $orderManager->getUserOrders($userId);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Soundex</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/shared.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 100px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #1a1a2e;
            border-bottom: 3px solid #3498db;
            display: inline-block;
            padding-bottom: 10px;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eef2f7;
            transition: transform 0.2s;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            background: #f8f9fa;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .order-info {
            display: flex;
            gap: 30px;
        }

        .info-group label {
            display: block;
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .info-group span {
            font-weight: 600;
            color: #333;
        }

        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-body {
            padding: 20px;
        }

        .no-orders {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
            color: #666;
        }

        .no-orders a {
            display: inline-block;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Your Order History</h1>
        </div>

        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <h2>No orders yet</h2>
                <p>Looks like you haven't placed any orders yet.</p>
                <a href="Gallery.php">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="info-group">
                                    <label>Order Placed</label>
                                    <span>
                                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="info-group">
                                    <label>Total</label>
                                    <span>₹
                                        <?php echo number_format($order['total_amount'], 2); ?>
                                    </span>
                                </div>
                                <div class="info-group">
                                    <label>Ship To</label>
                                    <?php
                                    $shipping = json_decode($order['shipping_address'], true);
                                    echo "<span>" . htmlspecialchars($shipping['firstName'] . ' ' . $shipping['lastName']) . "</span>";
                                    ?>
                                </div>
                            </div>
                            <div class="order-id">
                                <span style="color: #888; margin-right: 10px;">#
                                    <?php echo $order['order_number']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="order-body">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span class="order-status status-<?php echo strtolower($order['status']); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                    <span style="color: #666; margin-left: 15px; font-size: 0.9rem;">
                                        Payment:
                                        <?php echo ucfirst($order['payment_method']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>
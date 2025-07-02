<?php
session_start();
if (!isset($_SESSION['order_summary'])) {
    die("No order found. Please place an order first.");
}

$orders = $_SESSION['order_summary'];


unset($_SESSION['order_summary']);


$subtotal = 0;
$count = 0;
foreach ($orders as $order) {
    $subtotal += $order['price'] * $order['quantity'];
    $count += $order['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body {
            background-color: #f7c457;
            font-family: sans-serif;
            color: black;
            padding: 40px;
        }

        .shop{
            display: inline-block;
            margin-top: 30px;
            padding: 10px 40px;
            background-color: black;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .shop:hover {
            background-color: #222;
        }
        .all{
        font-size:18px;
        background-color: rgb(165, 24, 92);
        color: white;
        padding: 50px;
        height: auto;
        width: 350px;
        box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.48);
        border-radius: 10px;
        margin-left:500px
        }
        img {
    border-radius: 5px;
    object-fit: cover;
}

.logout {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    color: black;
    text-decoration: none;
}
.logout:hover {
    color: white;
}
    </style>
    </head>
    <a href="logout.php" class="logout" title = "Logout">⏻</a>
<body>
    <center><h2>Order placed successfully</h2></center>
<div class="all">
    <?php if ($count > 0): ?>
        <!-- Order ID (assuming all items share the same order_id) -->
        <p><strong style = "margin-left:30px;">Order ID:</strong> <?= htmlspecialchars($orders[0]['order_id']) ?></p>

        <!-- Items Table -->
        <table cellpadding="10" cellspacing="20">
            <thead>
                <tr>
                <th><u>Product Image</th><u>
                    <th><u>Product Name</th></u>
                    <th><u>Price (Rs.)</th></u>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                    <td>
                    <img src="<?= htmlspecialchars($order['image']) ?>" alt="Product Image" width="60" height="60">
                </td>
                        <td><?= htmlspecialchars($order['product_name']) ?>(<?= htmlspecialchars($order['quantity']) ?>)</td>
                        <td><?= number_format($order['price'] * $order['quantity'], 2) ?></td>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Subtotal -->
        <p style="font-size: 15px; font-weight: bold; margin-left:28px;">
            Subtotal (<?= $count ?> items): <span style="margin-left:30px;">Rs. <?= number_format($subtotal, 2) ?></span>
        </p>
    <?php else: ?>
        <p>No items found in the order.</p>
    <?php endif; ?>

    <a href="tst.php" style = "margin-left:80px;" class="shop">Back to shop</a>
    </div>
</body>
</html>

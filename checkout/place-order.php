<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "tst");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to place an order.");
}

$user_id = $_SESSION['user_id'];
$order_items = [];

// Start transaction
mysqli_begin_transaction($conn);

// 1. Create a new order
$order_stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id) VALUES (?)");
mysqli_stmt_bind_param($order_stmt, "i", $user_id);
mysqli_stmt_execute($order_stmt);
$order_id = mysqli_insert_id($conn);
mysqli_stmt_close($order_stmt);

// 2. Get all cart items for the user
$query = mysqli_prepare($conn, "SELECT c.product_id, c.product_name, c.price, c.quantity, c.image, p.quantity AS stock
                                FROM cart c 
                                JOIN products p ON c.product_id = p.product_id
                                WHERE c.user_id = ?");
mysqli_stmt_bind_param($query, "i", $user_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $price = $row['price'];
    $quantity = $row['quantity'] ?? 1;
    $image_path = $row['image'];
    $available_stock = $row['stock'];

    // Check stock
    if ($quantity > $available_stock) {
        mysqli_rollback($conn);
        die("Not enough stock for product: $product_name");
    }

    // Insert into order_items (store image path, not blob)
    $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
if (!$item_stmt) {
    mysqli_rollback($conn);
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($item_stmt, "iidi", $order_id, $product_id, $price, $quantity);
mysqli_stmt_execute($item_stmt);
mysqli_stmt_close($item_stmt);


    // Save to summary
    $order_items[] = [
        'order_id' => $order_id,
        'product_name' => $product_name,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $image_path 
    ];

    // Update stock
    $update_stock = mysqli_prepare($conn, "UPDATE products SET quantity = quantity - ? WHERE product_id = ?");
    if (!$update_stock) {
        mysqli_rollback($conn);
        die("Update stock prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($update_stock, "ii", $quantity, $product_id);
    mysqli_stmt_execute($update_stock);
    mysqli_stmt_close($update_stock);
}

// 3. Clear the user's cart
$delete_stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ?");
mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
mysqli_stmt_execute($delete_stmt);
mysqli_stmt_close($delete_stmt);

// 4. Commit transaction
mysqli_commit($conn);

// 5. Save order summary and redirect
$_SESSION['order_summary'] = $order_items;
header("Location: /php/checkout/confirmation.php");
exit();
?>

<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "tst");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $action = $_POST['action']; // 'increase' or 'decrease'

    // Get current quantity in cart
    $stmt = mysqli_prepare($conn, "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $cart_quantity = 0;
    if ($row = mysqli_fetch_assoc($result)) {
        $cart_quantity = intval($row['quantity']);
    }

    // Get available stock from products table
    $stock_stmt = mysqli_prepare($conn, "SELECT quantity FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($stock_stmt, "i", $product_id);
    mysqli_stmt_execute($stock_stmt);
    $stock_result = mysqli_stmt_get_result($stock_stmt);
    $available_stock = 0;
    if ($stock_row = mysqli_fetch_assoc($stock_result)) {
        $available_stock = intval($stock_row['quantity']);
    }

    // Adjust quantity
    if ($action === 'increase' && $cart_quantity < $available_stock) {
        $cart_quantity++;
    } elseif ($action === 'decrease' && $cart_quantity > 1) {
        $cart_quantity--;
    }

    // Update cart quantity
    $update_stmt = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($update_stmt, "iii", $cart_quantity, $user_id, $product_id);
    mysqli_stmt_execute($update_stmt);
}

header("Location: /php/cart/index.php");
exit;

<?php
session_start();

$product_id = $_POST['product_id'];
$action = $_POST['action'];

// Initialize cart quantity if not set
if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0;
}

if ($action === 'increase') {
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "tst");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch current stock for the product
    $product_id_safe = (int)$product_id;
    $sql = "SELECT quantity FROM products WHERE product_id = $product_id_safe";
    $result = mysqli_query($conn, $sql);
    $stock = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stock = $row['quantity'];
    }

    mysqli_close($conn);

    // Increase quantity only if less than stock
    if ($_SESSION['cart'][$product_id] < $stock) {
        $_SESSION['cart'][$product_id]++;
    }

} elseif ($action === 'decrease') {
    $_SESSION['cart'][$product_id]--;
    if ($_SESSION['cart'][$product_id] <= 0) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Redirect back to product page
header("Location: /php/catalog/shop.php");
exit;

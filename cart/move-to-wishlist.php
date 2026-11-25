<?php
session_start();
$servername = "localhost";
$Username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $Username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$product_id = $_POST['product_id'] ?? null;
$product_name = $_POST['product_name'] ?? '';
$price = $_POST['price'] ?? '';
$quantity = $_POST['quantity'] ?? '';
$target_file = $_POST['image'] ?? '';

if ($product_id) {
    // Check if already in cart
    $check = mysqli_prepare($conn, "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($check, "ii", $user_id, $product_id);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($res) === 0) {
        // Insert if not in cart
        $insert = mysqli_prepare($conn, "INSERT INTO wishlist (user_id, username, product_id, product_name, price, quantity,image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "isisiis", $user_id, $username, $product_id, $product_name, $price, $quantity, $target_file);
        mysqli_stmt_execute($insert);
        
        $delete = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($delete, "ii", $user_id, $product_id);
        mysqli_stmt_execute($delete);
    }

    header("Location: /php/cart/index.php"); // Redirect back
    exit;
}
?>
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "tst");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Delete the product from the wishlist
$stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
mysqli_stmt_execute($stmt);

header("Location: /php/wishlist/index.php");
exit;

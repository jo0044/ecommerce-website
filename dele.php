<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "tst");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    die("You must be logged in to add to wishlist.");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if($product_id){
$stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ? AND product_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}
header("Location: carts.php");
exit();
?>

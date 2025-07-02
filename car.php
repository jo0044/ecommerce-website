<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "tst");

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
    $check = mysqli_prepare($conn, "SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($check, "ii", $user_id, $product_id);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($res) === 0) {
        // Insert into database
        $insert = mysqli_prepare($conn, "INSERT INTO cart (user_id, username, product_id, product_name, price, image) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "isisis", $user_id, $username, $product_id, $product_name, $price, $target_file);

        mysqli_stmt_execute($insert);

        // Remove from wishlist
        $delete = mysqli_prepare($conn, "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($delete, "ii", $user_id, $product_id);
        mysqli_stmt_execute($delete);
    }


    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id]++;
    }

    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['cart_count'] = $count;

    header("Location: tst.php");
    exit;
}
?>

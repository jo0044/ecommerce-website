<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "tst");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    die("You must be logged in to view your cart.");
}

$user_id = $_SESSION['user_id'] ?? 0;
$wishlistcount = 0;
if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $wishlistcount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}


// Fetch cart items for this user
$query = mysqli_prepare($conn, "SELECT image, price, product_name, product_id, quantity FROM cart WHERE user_id = ?");
mysqli_stmt_bind_param($query, "i", $user_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

$cart = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cart[] = $row;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Your cart</title>
    <style>
    body {
        background-color: rgba(247, 196, 87, 0.95);
        padding: 40px;
        font-family: sans-serif;
        color: white;
    }
    .product-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-left: auto;
        margin-right: auto;
        margin-top:60px;
        width: 800px;
    }

    .product-card {
        background-color: black;
        color: white;
        border-radius: 8px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: 10px 11px 10px rgba(0, 0, 0, 0.3);
    }

    .product-card img {
        width: 120px;
        height: 120px;
        border-radius: 4px;
        object-fit: cover;
        margin-right: 15px; /* Space between image and product name */
    }

    .product-info {
        display: flex;
        flex-direction: row; /* Align product name beside image */
        align-items: center;
        gap: 10px; /* Space between image and name */
    }

    .product-info strong {
        font-size: 16px;
        margin-left: 10px;
    }

    /* Price stays at top-right */
    .product-price {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 16px;
        font-weight: bold;
    }

    /* Buttons at bottom-right */
    .product-actions {
        position:absolute;
        bottom: 15px;
        right: 100px;
        display: flex;
        gap: 10px;
    }

    .remove {
        background-color: #333;
        color: white;
        border: none;
        padding: 6px 10px;
        cursor: pointer;
        font-size: 14px;
        border-radius: 4px;
        width: auto;
    }

    .wish {
        position: absolute;
        color: rgb(165, 24, 92);
        top: 10px;
        right: 40px;
        font-size: 20px;
        text-decoration:none;
    }

    .wish:hover {
        color: black;
    }
    .cont{
        position:absolute;
        top:120px;
        left:370px;
        color:rgb(165, 24, 92);
    }
    .cont:hover {
        color: black;
    }
    .logout{
        position:absolute;
        color:black;
        top:10px;
        right:10px;
        font-size:20px;
        text-decoration:none;
    }
    .logout:hover{
        color:white;
    }
    </style>
</head>
<body>
<div>
    <a href="/php/catalog/shop.php" class="cont">Continue shopping</a>
</div>
<a href="/php/wishlist/index.php" class="wish">&#x2665;<sup style="font-size:10px"><?= $wishlistcount; ?></sup></a>
<a href="/php/auth/logout.php" class="logout" title="Logout">&#x23FB;</a>
<center><h2 style="color:black; margin-bottom:30px;">Cart</h2></center>
<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="product-grid">
    <?php 
        $subtotal = 0;
        $count = 0;
        foreach ($cart as $wish) {
        $subtotal += $wish['price'] * $wish['quantity'];
        $count += $wish['quantity'];
 ?>
    <div class="product-card">
        <div class="product-info">
            <img src="<?= htmlspecialchars($wish['image']) ?>" alt="Product Image">
            <strong style="font-size:25px; font-family:sans;"><?= htmlspecialchars($wish['product_name']) ?></strong>
        </div>

        <div class="product-price">Rs.<?= htmlspecialchars($wish['price']) ?></div>

        <div class="product-actions">
            <!-- Decrement quantity -->
            <form action="/php/cart/update.php" method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($wish['product_id']) ?>">
                <input type="hidden" name="action" value="decrease">
                <button type="submit" class="remove" style="width: 30px;">-</button>
            </form>

            <!-- Show quantity -->
            <span style="margin: 0 10px; font-weight:bold; color:white;"><?= $wish['quantity'] ?></span>

            <!-- Increment quantity -->
            <form action="/php/cart/update.php" method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($wish['product_id']) ?>">
                <input type="hidden" name="action" value="increase">
                <button type="submit" class="remove" style="width: 30px;">+</button>
            </form>

            <!-- Existing Remove button -->
            <form action="/php/cart/remove.php" method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($wish['product_id']) ?>">
                <button type="submit" class="remove">Remove</button>
            </form>

            <!-- Existing Move to wishlist button -->
            <form action="/php/cart/move-to-wishlist.php" method="POST" style="display:inline;">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($wish['product_id']) ?>">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($wish['product_name']) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($wish['price']) ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($wish['image']) ?>">
                <button type="submit" class="remove">Move to wishlist</button>
            </form> 
        </div>
    </div>
<?php } ?>

        <!-- Subtotal & Place order -->
<div style="align-self: flex-end; margin-top: 20px;">
    <p style="color:black; font-size:18px; font-weight:bold;">Subtotal (<?= $count ?> items): Rs.<?= number_format($subtotal, 2) ?></p>

    <form action="/php/checkout/place-order.php" method="POST" style="margin-top: 10px;">
        <button type="submit" style="padding: 10px 20px; background-color: black; color: white; border: none; border-radius: 6px; cursor: pointer;">
            Place Order
        </button>
    </form>
</div>
</div>

<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>


</body>
</html>

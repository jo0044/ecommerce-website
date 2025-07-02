<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "tst");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    die("You must be logged in to view your wishlist.");
}

$user_id = $_SESSION['user_id'];
$cartCount = 0;
if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cartCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Fetch wishlist items for this user
$query = mysqli_prepare($conn, "SELECT image, price, product_name, product_id FROM wishlist WHERE user_id = ?");
mysqli_stmt_bind_param($query, "i", $user_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

$wishlist = [];
while ($row = mysqli_fetch_assoc($result)) {
    $wishlist[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Wishlist</title>
    <style>
    body {
        background-color: rgba(247, 196, 87, 0.95);
        padding: 40px;/*The gap between the edge of the body on all sides and its contents */
        font-family: sans-serif;
        color: white;
    }
    .product-grid {
        display: flex;/*lays out the contents in a horizontal row by default*/
        flex-wrap: wrap;/*if this is not used, all the contents end up in the same line ans you have to scroll to the right till the end. This makes it go to the next line when the space in the current line is done*/
        gap: 20px;
        margin-left:50px;
    }

    .product-card {
        background-color: black;
        color: white;
        border-radius: 8px;
        width: 220px;
        box-shadow: 10px 11px 10px rgba(0,0,0,0.3);
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        box-sizing: border-box;
        height: 270px; /* Fixed height to ensure button stays at the bottom */
        overflow: hidden;
    }

    .product-card img {
        max-width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 4px;
    }
    .product-card strong {
        margin-top: 10px;
        text-align: center;
    }

    .product-card sup {
        display: block;
        margin-top: 5px;
    }
    .buttons {
        position: absolute;
        bottom: 15px;
        right: 23px;
        background-color: #333;
        color: white;
        border: none;
        padding: 6px 10px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 14px;
    }
    .cart{
        position: absolute;
        top: 10px;
        right: 40px; /* Adjust spacing between Wishlist and Cart */
        font-size: 20px;
        color: black;
        text-decoration: none;
    }
    .cart:hover {
    color: white;
}
    .remove{
        margin-top:4px;
        background-color: #333;
        color:white;
        border:none;
        padding: 6px 10px;
        cursor: pointer;
        font-size:14px;
        border-radius: 4px;
    }
        .back{
        position:absolute;
        font-size:16px;
        color:rgb(165, 24, 92);
        margin-top:40px;
        margin-left:650px;
    }
    .back:hover{
        color:black;
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
<a href="logout.php" class="logout" title = "Logout">⏻</a>
<a href="carts.php" class = "cart" title="Cart">🛒<sup style="font-size:10px"><?= $cartCount; ?></sup></a>
<center><h2 style = "color:black;">Wishlist</h2></center>
<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="product-grid">
        <?php foreach ($wishlist as $wish): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($wish['image']) ?>" alt="Product Image"><br>
                
                <form action="cart.php" method="POST" class="wishl" style="width: 100%;">
                    <strong><?= htmlspecialchars($wish['product_name']) ?></strong>
                    <sup>Rs.<?= htmlspecialchars($wish['price']) ?></sup>
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($wish['product_id']) ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($wish['product_name']) ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($wish['price']) ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($wish['image']) ?>">
                    <input type="hidden" name="quantity" value="1">

                    <span><button type="submit" class="buttons">Add to cart</button></span>
                </form>
                <form  action="del.php" method="POST" style = "margin-right:110px;">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($wish['product_id']) ?>">
                <button type="submit" class="remove">Remove</button>   
        </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Your wishlist is empty.</p>
<?php endif; ?>
<a href="tst.php" class = "back">Continue shopping</a>
</body>
</html>


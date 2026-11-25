<html>
<head>
    <title>Shop</title>
<style>
    body {
        background-color: rgba(247, 196, 87, 0.95);
        padding: 40px;/*The gap between the edge of the body on all sides and its contents */
        font-family: sans-serif;
        color: white;
    }

    .form-container {
        background-color: #333;
        padding: 15px;
        border-radius: 20px;/*basically the curved edges*/ 
        max-width: 300px;
        margin-bottom: 50px;/*Gap between the form and the products below it*/
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
        padding: 15px;
        width: 220px;
        box-shadow: 10px 11px 10px rgba(0,0,0,0.3);
        text-align: center;
    }

    .product-card img {
        max-width: 100%;
        height: 150px;
        /*object-fit: contain;*/
        border-radius: 0px;
        margin-bottom: 15px;
    }

    .product-card p {
        margin: 4px 0;
        font-size: 14px;
    }

    select {
        width: 100%;
        padding: 8px;
    }

    button {
        margin-top: 10px;
        padding: 8px;
        width: 100%;
        background-color: black;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .button:hover {
        background-color: #ddd;
        color: black;
        opacity:80%;
    }
    .back{
        position:absolute;
        font-size:16px;
        top: 10px;
        left:10px;
        color:rgb(165, 24, 92);
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
    .wishl {
    background: none;
    color: red;
    font-size: 20px;
    border: none;
    padding: 5px 10px;
    border-radius: 10px;
    margin-top: 10px;
    cursor: pointer;
    }
    .wish{
        position:absolute;
        /*color:rgb(165, 24, 92);*/
        top:10px;
        right:40px;
        font-size:20px;
        text-decoration: none;
    }
    .wish:hover{
        color:white;
    }
    .wi:hover{
        background-color: #ddd;
        color: black;
    }
    .cart {
    position: absolute;
    top: 10px;
    right: 80px; /* Adjust spacing between Wishlist and Cart */
    font-size: 20px;
    color: black;
    text-decoration: none;
}

.cart:hover {
    color: white;
}

 
</style>
</head>
<body>
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$user_id = $_SESSION['user_id'] ?? 0;
$cartCount = 0;
$wishlistcount = 0;

if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cartCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $wishlistcount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

?>
    
<a href="/php/wishlist/index.php" class="wish" title="Wishlist">&#x2665;<sup style="font-size:10px"><?= $wishlistcount; ?></sup></a>
<a href="/php/auth/logout.php" class="logout" title="Logout">&#x23FB;</a>
<?php
function inp($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['category_id'])) {
    $_SESSION['selected_category'] = $_POST['category_id'] === "All" ? 0 : (int) $_POST['category_id'];
    header("Location: /php/catalog/shop.php");
    exit;
}

$sel_categ = $_SESSION['selected_category'] ?? 0;
$products = [];

    if ($sel_categ == 0) {
        $sql = "SELECT * FROM products";
        $stmt = mysqli_prepare($conn, $sql);
    } else {
        $sql = "SELECT * FROM products WHERE category_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $sel_categ); // because category_id is an integer

    }

    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_stmt_close($stmt);
    }

?>
<!-- Show cart icon with item count -->
<a href="/php/cart/index.php" class="cart" title="Cart">
    Cart<sup style="font-size:10px"><?= $cartCount; ?></sup>
</a>

<!-- Category Select Form -->
 <center>
<div class="form-container">
    <form method="post" action="">
        <label for="category_id">Select Category:</label>
        <select name="category_id" id="category_id">
            <option value="All" <?= $sel_categ == 0 ? "selected" : "" ?>>All</option>
            <?php
        $sql = "SELECT category_id, category FROM categories";
$res = mysqli_query($conn, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        echo '<option value="' . htmlspecialchars($row['category_id']) . '" ' . 
             ($sel_categ == $row['category_id'] ? 'selected' : '') . '>' . 
             htmlspecialchars($row['category']) . 
             '</option>';
    }
        } else {
            echo '<option disabled>No categories available</option>';
        }
        ?>
        </select>
        <button type="submit" class = "button">Show Products</button>
    </form>
</div>
</center>
<!-- Products Grid -->
<div class="product-grid">
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image"><br>
            <strong><?= htmlspecialchars($product['product_name']) ?></strong><br><sup>Rs.<?= htmlspecialchars($product['price']) ?></sup><br>
            <p>Only <strong><?= htmlspecialchars($product['quantity']) ?></strong> left in stock</p>
            <!--<p><strong>Category:</strong> <?= htmlspecialchars($product['category_id']) ?></p>-->
            <p><?= htmlspecialchars($product['description']) ?></p>

        <form action="/php/wishlist/add.php" method="POST" class="wishl">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>">
        <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']) ?>">

        <!--<input type="hidden" name="quantity" value="<?//= htmlspecialchars($product['quantity']) ?>">-->
        <button type="submit" class="wi">Add to wishlist</button>
    </form>
    <form action="/php/cart/add.php" method="POST" class="wishl">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'])?>">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name'])?>">
        <input type="hidden" name="price" value="<?= htmlspecialchars($product['price'])?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($product['image'])?>">
        <button type="submit" class="wi">Add to cart</button> </form>    
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
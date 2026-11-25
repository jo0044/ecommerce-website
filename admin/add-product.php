<html>
<head>
    <title>Admin</title>
<style>
    body {
        background-image: url("https://img.freepik.com/free-photo/black-friday-elements-assortment_23-2149074076.jpg?t=st=1746599379~exp=1746602979~hmac=ef9de24cc2176ff54e132517d16b6d4c698b9534b49d5c86bee2b9fc434f2efb&w=1380");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-start; 
        padding-left: 10%; 
    }

    .admin {
        font-size:18px;
        background-color: rgb(165, 24, 92);
        color: white;
        padding: 50px;
        height: auto;
        width: 230px;
        border-radius:15px;
        box-shadow: 10px 11px 10px rgba(0,0,0,0.3);

    }
    .button{
        background-color:black;
        color:white;
        border:none;
        padding:6px;
    }
    .button:hover{
        background-color:#ddd;
        color:black;
    }
    .error::placeholder {
    color: red;
    }   
    .back{
            position:absolute;
            font-size:25px;
            top:10px;
            left:10px;
            color:rgb(165, 24, 92);
            text-decoration:none;
        }
        .back:hover{
            color:white;
        }
        .logout{
                position:absolute;
                font-size:20px;
                top:10px;
                right:10px;
                color:black;
                text-decoration:none;
            }
            .logout:hover{
                color:white;
            }
            
</style>
</head>
    <body>
<a href="http://localhost/php/aa.htm" class="back">&larr;</a>
<a href="logout.php" class="logout" title = "Logout">⏻</a>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$category = $product_name = $price = $quantity = $description = "";
$msg = $product_nameErr =  $priceErr =  $quantityErr = $descriptionErr = "";

function inp($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $category = inp($_POST["category_id"]);
    if(empty($_POST["product_name"])){
        $product_nameErr = "Please enter product name";
    }else{
        $product_name = inp($_POST["product_name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/",$product_name)) {
            $product_nameErr = "Only letters, spaces, hyphens, and apostrophes are allowed";
        }        
    }
    if(empty($_POST['price'])){
        $priceErr = "Please enter a valid price.";
    }else{
        $price = inp($_POST["price"]);
        if(!preg_match('/^\d+(\.\d{1,2})?$/', $price)){
            $priceErr = "Only numbers are allowed";
        }
    }
    if(empty($_POST["quantity"])){
        $quantityErr = "Please enter quantity." ;
    }else{
        $quantity = inp($_POST["quantity"]);
        if(!preg_match("/^[0-9]+$/", $quantity)){
            $quantityErr = "Only numbers are allowed.";
        }
    }
    if(empty($_POST["description"])){
        $descriptionErr = "Please enter the description.";
    }else{
        $description = inp($_POST["description"]);
    }  

    // Validate that a category is selected
    if (!empty($category) && $category != 'Select') {

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Sanitize the file name
            $file_name = basename($_FILES['image']['name']);
            $file_name = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", $file_name);

            $target_dir = "uploads/";
            $target_file = $target_dir . $file_name;

            $image_type = mime_content_type($_FILES['image']['tmp_name']);
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image_type, $allowed_types)) {
                if ($_FILES['image']['size'] < 5000000) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        if($product_nameErr || $priceErr || $quantityErr || $descriptionErr){}
                        else{
                        $sql = "INSERT INTO products (category_id, product_name, price, quantity,description, image) 
                                VALUES ('$category', '$product_name', '$price', '$quantity', '$description', '$target_file')";
                        
                        if (mysqli_query($conn, $sql)) {
                            $msg = "Product added successfully!";
                        } else {
                            $msg = "Error: " . mysqli_error($conn);
                        }
                        }
                    } else {
                        $msg = "Sorry, there was an error uploading your image.";
                    }
                } else {
                    $msg = "Sorry, your image is too large.";
                }
            } else {
                $msg = "Sorry, only JPEG, PNG, and GIF files are allowed.";
            }
        } else {
            $msg = "No image uploaded.";
        }

    } else {
        $msg = "Please select a valid category.";
    }
}


?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data" class="admin">
<?php
    if (!empty($msg)) echo "<div style='color:rgb(246, 218, 40);'>$msg</div>";
?><br>
<label for="category">Select Category: </label><br>
    <select name="category_id" id="category_id">
    <option value="Select">-Select-</option>
    <?php
    $sql = "SELECT category_id, category FROM categories";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<option value="' . $row['category_id'] . '">' . htmlspecialchars($row['category']) . '</option>';
        }
    } else {
        echo '<option disabled>No categories available</option>';
    }
    ?>
</select>
<br><br>    
    <label for="product_name">Product name:</label><br>
    <input type="text" name="product_name"
    class="<?php echo !empty($product_nameErr) ? 'error' : ''; ?>"
    value="<?php echo empty($product_nameErr) ? htmlspecialchars($product_name) : ''; ?>" 
    placeholder="<?php echo !empty($product_nameErr) ? $product_nameErr : ''; ?>"><br><br>
    <label for="price">Price:</label><br>
    <input type="text" name="price"
    class="<?php echo !empty($priceErr) ? 'error' : ''; ?>"
    value="<?php echo empty($priceErr) ? htmlspecialchars($price) : ''; ?>" 
    placeholder="<?php echo !empty($priceErr) ? $priceErr : ''; ?>"><br><br>
    <label for = "quantity">Quantity:</label><br>
    <input type="text" name="quantity"
    class="<?php echo !empty($quantityErr) ? 'error' : ''; ?>"
    value="<?php echo empty($quantityErr) ? htmlspecialchars($quantity) : ''; ?>" 
    placeholder="<?php echo !empty($quantityErr) ? $quantityErr : ''; ?>"><br><br>
    
    Select image to upload: <input type="file" name="image" class = "img"><br><br>
    <label for = "description">Description:</label><br>
    <textarea name="description" rows="7" cols="30" 
    style = "border-radius:4px; margin-left:0;"
    class="<?php echo !empty($descriptionErr) ? 'error' : ''; ?>"
    value="<?php echo empty($descriptionErr) ? htmlspecialchars($description) : ''; ?>" 
    placeholder="<?php echo !empty($descriptionErr) ? $descriptionErr : ''; ?>"></textarea><br><br>
    
    <input type="submit" value="Add Product" class="button">
</form>


</body>
</html>

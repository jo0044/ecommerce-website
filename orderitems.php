<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}


$sql = "CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
)";
if(mysqli_query($conn, $sql)){
    echo "table created successfully";
}else{
    echo "error ". mysqli_error($conn);
}
mysqli_close($conn);
?>
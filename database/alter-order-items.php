<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ".mysqli_error());
}

$sql = "ALTER TABLE order_items 
ADD COLUMN product_id INT(20) UNSIGNED,
ADD CONSTRAINT fk_product
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE SET NULL";

if(mysqli_query($conn, $sql)){
    echo "altering was successful";
}else{
    echo "error". mysqli_error($conn);
}
mysqli_close($conn);
?>
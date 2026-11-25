<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst"; 
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}
$sql = "CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    description TEXT,
    image BLOB,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
)";
if(mysqli_query($conn, $sql)){
    echo "Table created successfully.";
} else{
    echo "Error: ".mysqli_error($conn);
}
mysqli_close($conn);
?>
<?php
$servername = "localhost";
$Username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $Username, $password, $dbname);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}
$sql = "CREATE TABLE cart(
    user_id INT(20),
    username VARCHAR(50) NOT NULL,
    product_id INT(20),
    product_name VARCHAR(50),
    price DECIMAL(20,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    image LONGBLOB,
    PRIMARY KEY (user_id, product_id))";
    if(mysqli_query($conn, $sql)){
        echo "Table created successfully";
    }else{
        echo "Error: ". mysqli_error($conn);
    }
    mysqli_close($conn);
    ?>
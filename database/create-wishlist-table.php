<?php
$servername = "localhost";
$usernam = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $usernam, $password, $dbname);
if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}

$sql = "CREATE TABLE wishlist(
user_id INT(20),
username VARCHAR(50) NOT NULL,
product_id INT(20),
product_name VARCHAR(50),
price DECIMAL(20,2) NOT NULL,
quantity INT(20),
image LONGBLOB)";
if(mysqli_query($conn, $sql)){
    echo "Table created successfully";
}else{
    echo "Error: ". mysqli_error($conn);
}
mysqli_close($conn);
?>
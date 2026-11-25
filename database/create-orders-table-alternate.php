<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}
$sql = "CREATE TABLE orders (
order_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if(mysqli_query($conn, $sql)){
    echo "table created succesfully";
}else{
    echo "error creating table". mysqli_error($conn);
}
mysqli_close($conn)
?>
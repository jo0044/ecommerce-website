<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

$sql = "ALTER TABLE products
ADD COLUMN category_id INT(20) UNSIGNED,
ADD CONSTRAINT fk_category
  FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL";

if(mysqli_query($conn, $sql)){
    echo "alter successful";
}else{
    echo "error".mysqli_error($conn);
}

mysqli_close($conn)
?>
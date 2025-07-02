<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}

$sql = "CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL
)";

if(mysqli_query($conn, $sql)){
    echo "Table categories created successfully.";
}else{
    echo "Error creating table.". mysqli_error($conn);
}
mysqli_close($conn);
?>
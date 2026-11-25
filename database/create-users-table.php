<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

$sql = "CREATE TABLE users(
user_id INT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(20) NOT NULL,
email VARCHAR(30) NOT NULL,
mobile INT(10) NOT NULL,
gender VARCHAR(10) NOT NULL,
username VARCHAR(30) NOT NULL,
password VARCHAR(255) NOT NULL)";

if(mysqli_query($conn, $sql)){
    echo "table created successfully";
}else{
    echo "Error: ". mysqli_error($conn);
}

mysqli_close($conn);
?>
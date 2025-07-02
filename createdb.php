<?php
$servername = "localhost";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}
$sql = "CREATE DATABASE tst";
if(mysqli_query($conn, $sql)){
    echo "Created database successfully.";
}else{
    echo "Error: ".mysqli_error($conn);
}
mysqli_close($conn);
?>
<html>
    <style>
        body{
        background-image: url("https://img.freepik.com/free-photo/black-friday-elements-assortment_23-2149074076.jpg?t=st=1746599379~exp=1746602979~hmac=ef9de24cc2176ff54e132517d16b6d4c698b9534b49d5c86bee2b9fc434f2efb&w=1380");
        background-size:cover;
        background-repeat:no-repeat;
        margin:0;
        padding:0;
        height:100vh;
        display:flex;
        align-items:center;
        justify-content:flex-start;
        padding-left:100px;
        }
        .admin {
        font-size:18px;
        background-color: rgb(165, 24, 92);
        color: white;
        padding: 50px;
        height: auto;
        width: 230px;
        border-radius:15px;
        box-shadow: 10px 11px 10px rgba(0,0,0,0.3);
        }
        .submit:hover {
        background-color: #ddd;
        color: black;
        }
        .submit {
        margin-right:50px;
        margin-top: 10px;
        padding: 8px;
        width: 100%;
        background-color: black;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 10px;
        }
        .error::placeholder {
        color: red;
        }
        .back{
            position:absolute;
            font-size:25px;
            top:10px;
            left:10px;
            color:rgb(165, 24, 92);
            text-decoration:none;
        }
        .back:hover{
            color:white;
        }
        .logout{
                position:absolute;
                font-size:20px;
                top:10px;
                right:10px;
                color:black;
                text-decoration:none;
            }
            .logout:hover{
                color:white;
            }
    </style>
    <body>
    <a href="http://localhost/php/aa.htm" class="back">&larr;</a>
    <a href="logout.php" class="logout" title = "Logout">⏻</a>
<?php
$servername = "localhost";
$username  = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: ". mysqli_connect_error());
}


$category = "";
$msg = "";
function inp($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    if(empty($_POST['category'])){
        $msg = "Please enter a category.";
    }else{
    $category = inp($_POST["category"]);
    $exist = "SELECT * FROM categories WHERE category = '$category'";
    $res = mysqli_query($conn, $exist);

    if(mysqli_num_rows($res)>0){
        $msg = "Category already exists.";
    }else{
        $sql = "INSERT INTO categories(category) VALUES('$category')";
        if(mysqli_query($conn,$sql)){
            $msg = "Category added successfully!";
        }else{
            $msg = "Error: ". mysqli_error($conn);
        }
    }
}
}
?>

<form method="post" action="" class = "admin">
    <label for = "category" style="font-family:sans serif ">Enter a new category:</label><br>
    <input type = "text" name = "category"
    class="<?php echo !empty($msg) ? 'error' : ''; ?>"
    placeholder="<?php echo !empty($msg) ? $msg : ''; ?>" 
    style="margin-top:10px; width:230px"></input><br><br>
    <input type = "submit" value = "Add category" class="submit">
    </form>
</body>
</html>
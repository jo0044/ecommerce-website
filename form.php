<html>
    <head>
        <title>Login</title>
<style>
    body {
        background-image: url("https://img.freepik.com/free-photo/black-friday-elements-assortment_23-2149074076.jpg?t=st=1746599379~exp=1746602979~hmac=ef9de24cc2176ff54e132517d16b6d4c698b9534b49d5c86bee2b9fc434f2efb&w=1380");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-start; /* Align form towards the left */
        padding-left: 10%; /* Adjust this for horizontal positioning */
    }

    .login-form {
        font-size:18px;
        background-color: rgb(165, 24, 92);
        color: white;
        padding: 50px;
        height: 200px;
        width: 300px;
        box-shadow: 10px 11px 10px rgba(139, 9, 9, 0.48);
        border-radius: 10px;
        margin-left:20px
    }
    .submi{
        background-color:black; 
        color:white; 
        margin-left:40px; 
        padding:4px; 
        width:75px;
        border:none;
    }
    .fr:hover {
    background-color: #ddd;
    color: black;
    }
    .same{
        background-color:black;
        color:white; 
        margin-left:10px; 
        padding:4px; 
        width:75px; 
        border:none;
    }

</style>
</head>
<body>
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function inp($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
$msgerr = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = inp($_POST["username"]);
    $password = inp($_POST["password"]);
    if(empty($username) && empty($password)){
        $msgerr = "Please enter your credentials.";
    }else{
    if (empty($username)) {
        $msgerr = "Please enter a valid username.";
    } elseif (empty($password)) {
        $msgerr = "Please enter a valid password.";
    } else{

    $sql = "SELECT user_id, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $hashed_password);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_fetch($stmt);
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id; 
                $_SESSION['username'] = $username;
                if ($username === 'admin') {
                    header("Location: http://localhost/php/aa.htm");
                }
                 else {
                    header("Location: http://localhost/php/tst.php");
                }
    
                exit();
            } else {
                $msgerr = "Wrong password.";
            }
        } else {
            $msgerr = 'User does not exist. Please <a href="http://localhost/php/register.php" style="color:rgb(41, 211, 101);">register</a><br><br>';
        }
    
        mysqli_stmt_close($stmt);
     
    } else {
        $msgerr = "SQL error: " . mysqli_error($conn);
    }
}
}
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login-form">
    
    <?php if (!empty($msgerr)) echo "<div style='color:rgb(246, 218, 40); margin-left:28px;'>$msgerr</div>"; ?>
    <label for="username" style = "margin-left:30px;">Username:</label>
    <input type="text" name="username"  placeholder="Please enter your username" style="width:70%; margin-left:30px;"><br><br>
    <label for="password" style = "margin-left:30px;">Password:</label>
    <input type="password" name="password" placeholder="Please enter your password" style = "width:70%; margin-left:30px; margin-bottom:20px"><br><br>
    <input type="submit" value="Login" class="submi fr" >
    <button type="button" class="same fr" onclick="window.location.href='http://localhost/php/register.php'">Register</button>
</form>
</body>
</html>



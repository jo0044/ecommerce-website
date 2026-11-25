<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
    <body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tst";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$name = $email = $gender = $mobile = "";
$nameErr = $emailErr = $genderErr = $mobileErr = $usernameErr = $passwordErr = "";
function inp($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  //firstname
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = inp($_POST["name"]);
    // check if name only contains letters
    if (!preg_match("/^[a-zA-Z-']*$/",$name)) {
      $nameErr = "Only letters are allowed";
    }
  }
  //email
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = inp($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
//gender
  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = inp($_POST["gender"]);
  }
  
//mobile
  if(empty($_POST["mobile"])){
    $mobileErr = "Mobile is required";
  }else{
    $mobile = inp($_POST["mobile"]);
    if(!preg_match("/^[0-9]+$/", $mobile)){
        $mobileErr = "Only numbers are allowed";
    }
  }
  if (empty($_POST["username"])) {
    $usernameErr = "username is required";
  } else {
    $username = inp($_POST["username"]);
  }
  if (empty($_POST["password"])) {
    $passwordErr = "password is required";
  } else {
    $password = password_hash(inp($_POST["password"]), PASSWORD_DEFAULT);
  }

    $exist = "SELECT * FROM users WHERE username = '$username'";
    $res = mysqli_query($conn, $exist); 
    
    if (mysqli_num_rows($res) > 0) {
        echo "Username already exists. Please use another username or try logging in.<br><br>";
    } else {
        $sql = "INSERT INTO users(name, email, mobile, gender, username, password) VALUES('$name', '$email', '$mobile', '$gender', '$username', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo 'Registered successfully! Please try to <a href="/php/auth/login.php">login</a>.<br><br>';
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"  style = "background-color:powderblue; color:black; padding:20px; border:1px; height:380px; width:300px;">
    <h2>Registration Form</h2>
    <p><span class="error">* required field</span></p>
    Name: <input type="text" name = "name">
    <span class="error">* <?php echo $nameErr;?></span><br><br>
    Email: <input type="text" name = "email">
    <span class="error">* <?php echo $emailErr;?></span><br><br>
    Mobile: <input type="tel" name="mobile">
    <span class = "error">* <?php echo $mobileErr;?></span><br><br>
    Gender:
    <input type="radio" name="gender" value="female">Female
    <input type="radio" name="gender" value="male">Male
    <input type="radio" name="gender" value="other">Other
    <span class="error">* <?php echo $genderErr;?></span><br><br>
    Create a username: <input type="text" name="username">
    <span class="error">* <?php echo $usernameErr;?></span><br><br>
    Create a password: <input type="password" name="password">
    <span class="error">* <?php echo $passwordErr;?></span><br><br><br><br>
    <input type="submit" name="submit">
</form>

</body>
</html>
<html>
<head>
  <title>Register</title>
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

    .register {
        font-size:16px;
        background-color: rgb(165, 24, 92);
        color: white;
        padding: 50px;
        height: auto;
        width: 300px;
        font-family: sans-serif;
        border-radius:20px;
        box-shadow: 10px 11px 10px rgba(0,0,0,0.3);
    }
    .error::placeholder {
    color: red;
}
    .submi{
    color:rgb(200, 255, 0); 
    font-size:20px;
    }
    .how:hover{
      background-color:#ddd;
      color:black;
    }
    .how{
      background-color:black;
      color:white;
      border:none;  
    }
</style>
</head>
    <body>
<?php
$servername = "localhost";
$Username = "root";
$password = "";
$dbname = "tst";

$conn = mysqli_connect($servername, $Username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$name = $email = $gender = $mobile = $username = "";
$nameErr = $emailErr = $genderErr = $mobileErr = $usernameErr = $passwordErr = $msg = "";
function inp($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
  if (empty($_POST["gender"]) || $_POST["gender"]=="none") {
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
  if ($nameErr || $emailErr || $genderErr || $mobileErr || $usernameErr || $passwordErr) {
          
} else{
    $exist = "SELECT * FROM users WHERE username = '$username'";
    $res = mysqli_query($conn, $exist); 
    
    if (mysqli_num_rows($res) > 0) {
        $usernameErr = "Username already exists.";
        $msg = "Please try another username or try <a href='/php/auth/login.php'>logging</a> in.<br><br>";
    } else {
        $sql = "INSERT INTO users(name, email, mobile, gender, username, password) VALUES('$name', '$email', '$mobile', '$gender', '$username', '$password')";
        if (mysqli_query($conn, $sql)) {
            $msg = "Registered successfully! Please try to <a href='/php/auth/login.php'>login</a>.<br><br>";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
}
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>"  class = "register">
    <strong style = "margin-left:20px; font-size:27px">Registration Form</strong><br>
    <sup><span class="error" style = "margin-left:20px; color:rgb(200, 255, 0)">* required field</span></sup><br>
    <span style="color:rgb(200, 255, 0); font-size:16px; margin-left:20px;"><?php echo $genderErr;?></span><br>
    <?php
    if (!empty($msg)) echo "<div style= 'color:rgb(200, 255, 0);'>$msg</div>";
    ?>
    <label for="name" style = "margin-left:20px">Name:</label>
    <input type="text" name = "name" 
    class="<?php echo !empty($nameErr) ? 'error' : ''; ?>"
    value="<?php echo empty($nameErr) ? htmlspecialchars($name) : ''; ?>" 
    placeholder="<?php echo !empty($nameErr) ? $nameErr : ''; ?>" 
    style = "margin-left:19px;">
     <sup class="submi"> *</sup><br><br>
    <label for="email" style = "margin-left:20px">Email:</label>
    <input type="text" name = "email"  
    class="<?php echo !empty($emailErr) ? 'error' : ''; ?>"
    value="<?php echo empty($emailErr) ? htmlspecialchars($email) : ''; ?>" 
    placeholder="<?php echo !empty($emailErr) ? $emailErr : ''; ?>"  
    style = "margin-left:21px; width:172px;">
    <sup class="submi"> *</sup><br><br>
    <label for="mobile" style = "margin-left:21px">Mobile:</label>
    <input type="tel" name="mobile" 
    class="<?php echo !empty($mobileErr) ? 'error' : ''; ?>"
    valye="<?php echo empty($mobileErr) ? htmlspecialchars($mobile) : ''; ?>"
    placeholder="<?php echo !empty($mobileErr) ? $mobileErr : ''; ?>" 
    style = "margin-left:14px; width:172px;">
    <sup class="submi"> *</sup><br><br>

    <label for="gender" style = "margin-left:20px">Gender: </label>
    <select name="gender" id="gender" style="margin-left:10px; width:170px;"> 
    <option value="none">Select</option>
    <option value="female" <?php echo ($gender == "female") ? "selected" : ""; ?>>Female</option>
    <option value="male" <?php echo ($gender == "male") ? "selected" : ""; ?>>Male</option>
    <option value="other" <?php echo ($gender == "other") ? "selected" : ""; ?>>Other</option></select>
    <sup class="submi"> *</sup><br><br>

    <label for="username" style = "margin-left:20px;">Username:</label>
    <input type="text" name="username" 
    class="<?php echo !empty($usernameErr) ? 'error' : ''; ?>"
    value="<?php echo empty($usernameErr) ? htmlspecialchars($username) : ''; ?>"
    placeholder="<?php echo !empty($usernameErr) ? $usernameErr : ''; ?>" 
    style = "margin-left:0px; width:160px;">
    <sup class="submi"> *</sup><br><br>
    <label for="password" style = "margin-left:20px">Password:</label> 
    <input type="password" name="password" 
    class="<?php echo !empty($passwordErr) ? 'error' : ''; ?>"
    placeholder="<?php echo !empty($passwordErr) ? $passwordErr : ''; ?>" 
    style = "margin-left:0px; width:163px;">
    <sup class="submi"> *</sup><br><br>
    <input type="submit" name="submit" class="how" style = "margin-left:90px;">
    <button type="button" class="how" onclick="window.location.href='/php/auth/login.php'">Login</button>
</form>

</body> 
</html>
<?php
include "db.php";

$msg = "";
$showLogin = false;

if(isset($_POST['submit'])){
    // sanitize inputs to prevent SQL syntax errors (e.g., apostrophes in names)
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = "user";

    $check = "SELECT * FROM users WHERE email='$email'";
    $resultCheck = mysqli_query($conn, $check);

    if(mysqli_num_rows($resultCheck) > 0){
        $msg = "Email already exists!";
    } else {

        $sql = "INSERT INTO users (name,email,password,phone,address,role)
            VALUES ('$name','$email','$password','$phone','$address','$role')";

        $result = mysqli_query($conn,$sql);

        if(!$result){
            $msg = "Error: {$conn->error}";
        }
        else{
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KARTIFY Register</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

.main{
    height:100vh;
    background:#f5f6fa;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    background:white;
    padding:40px;
    width:420px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    text-align:center;
}

.card h2{
    margin-bottom:10px;
}

.msg{
    margin-bottom:15px;
    font-size:14px;
    color:#e74c3c;
}

.success{
    color:green;
}

.input-group{
    margin-bottom:15px;
    text-align:left;
}

.input-group label{
    display:block;
    margin-bottom:5px;
    font-size:14px;
    font-weight:500;
    color:#333;
}

.input-group input,
.input-group textarea{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:6px;
    outline:none;
}

.btn{
    width:100%;
    padding:12px;
    border:none;
    background:#111;
    color:white;
    cursor:pointer;
    border-radius:6px;
    margin-top:10px;
}

.btn:hover{
    background:#333;
}

.back-home{
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 10px 14px;
    background: white;
    border: 1px solid #111;
    color: #111;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

.back-home:hover{
    background: #111;
    color: white;
}

.footer-redirect a {
    color: black;
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid black;
}

    
</style>
</head>

<body>

<div class="main">

    <a href="index.php" class="back-home">← Back to Home</a>

    <div class="card">

        <h2>Create Account</h2>

        <?php if($msg != ""){ ?>
            <div class="msg <?php if($showLogin) echo 'success'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php } ?>

        <form method="post">

            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required>
            </div>

            <div class="input-group">
                <label>Address</label>
                <textarea name="address"></textarea>
            </div>

            <button class="btn" type="submit" name="submit">
                Sign Up
            </button>

        </form>

        <div class="footer-redirect">
            Already have an account? <a href="login.php">Log In Here</a>
        </div>

    </div>

</div>


</body>
</html>
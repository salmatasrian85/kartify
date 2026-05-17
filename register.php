<?php
include "db.php";

$msg = "";
$showLogin = false;

/* HANDLE FORM SUBMISSION */
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // keeping your style
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = "user";

    /* CHECK IF EMAIL ALREADY EXISTS */
    $check = "SELECT * FROM users WHERE email='$email'";
    $resultCheck = mysqli_query($conn, $check);

    if(mysqli_num_rows($resultCheck) > 0){

        $msg = "Email already exists!";

    } else {

        // insert user
        $sql = "insert into users(name,email,password,phone,address,role)
                values('$name','$email','$password','$phone','$address','$role')";

        $result = mysqli_query($conn,$sql);

        if(!$result){
            $msg = "Error: {$conn->error}";
        }
        else{
            $msg = "Registered Successfully!";
            $showLogin = true; // show login button
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

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

/* MAIN */
.main{
    height:100vh;
    background:#f5f6fa;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* CARD */
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

.card p{
    color:#666;
    margin-bottom:20px;
}

/* MESSAGE */
.msg{
    margin-bottom:15px;
    font-size:14px;
    color:#e74c3c;
}

/* SUCCESS */
.success{
    color:green;
}

/* INPUT */
.input-group{
    margin-bottom:15px;
    text-align:left;
}

.input-group input,
.input-group textarea{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:6px;
    outline:none;
}

/* BUTTON */
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

/* LOGIN BUTTON */
.login-btn{
    display:block;
    margin-top:15px;
    padding:12px;
    background:#2ecc71;
    color:white;
    text-decoration:none;
    border-radius:6px;
}

.login-btn:hover{
    background:#27ae60;
}
.footer-redirect a {
      color: black;
      font-weight: 600;
      text-decoration: none;
      border-bottom: 1px solid black;
      margin-top: 40px;
    }

</style>
</head>

<body>

<div class="main">

    <div class="card">

        <h2>Create Account</h2>

        <!-- MESSAGE -->
        <?php if($msg != ""){ ?>
            <div class="msg <?php if($showLogin) echo 'success'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php } ?>

        <!-- FORM -->
        <form method="post">

            <div class="input-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <input type="text" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="input-group">
                <textarea name="address" placeholder="Address"></textarea>
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
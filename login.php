<?php
include "db.php";
session_start();

$msg = "";

/* HANDLE LOGIN */
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if($result->num_rows > 0){
        $row = mysqli_fetch_assoc($result);

        if($row['password'] == $password){

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];

            if($_SESSION['user_role'] == "admin"){
                header("Location: admin/dashboard.php");
                exit();
            } else {
                header("Location:index.php");
                exit();
            }

        } else {
            $msg = "Wrong password!";
        }

    } else {
        $msg = "Account not found. Please sign up.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KARTIFY Login</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

/* MAIN WRAPPER */
.main{
    height:100vh;
    background:#f5f6fa;
    display:flex;
    justify-content:center;
    align-items:center;
    position: relative;
}

/* BACK TO HOME BUTTON */
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

/* CARD */
.card{
    background:white;
    padding:40px;
    width:400px;
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
    color:#e74c3c;
    margin-bottom:15px;
    font-size:14px;
}

/* INPUT */
.input-group{
    margin-bottom:15px;
    text-align:left;
}

.input-group input{
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
    transition:0.3s;
}

.btn:hover{
    background:#333;
}

/* LINK */
.link{
    margin-top:15px;
    font-size:14px;
}

.link a{
    text-decoration:none;
    color:#111;
    font-weight:600;
}

.link a:hover{
    text-decoration:underline;
}


    
</style>
</head>

<body>

<div class="main">

    <!-- BACK TO HOME -->
    <a href="index.php" class="back-home">← Back to Home</a>

    <div class="card">

        <h2>Welcome Back</h2>
        <p>Login to your account</p>

        <!-- ERROR MESSAGE -->
        <?php if($msg != ""){ ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php } ?>

        <form method="post">

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button class="btn" type="submit" name="submit">
                Login
            </button>

        </form>

        <div class="link">
            Don't have an account? <a href="register.php">Sign Up</a>
        </div>

    </div>

</div>


</body>
</html>
<?php
include "db.php";
session_start();
    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "select * from users where email = '$email'";
        $result = mysqli_query($conn,$sql);
     if(!$result->num_rows>0){
          $row =  mysqli_fetch_assoc($result);
            if ($row['password'] ==$password){
            $SESSION['user_id'] = $row['id'];
            $SESSION['user_name'] = $row['name'];
            $SESSION['user_role'] = $row['role'];
            if($SESSION['user_role'] == "admin"){
                header("Location: admin/dashboard.php");
            }
            else{
            echo "Dashboard for user";
            }
        }
           else{
            echo "Wrong Password!";
           }
        }
        else{
            echo "Please go for signup!";
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .login{
            position: fixed;
            top: 25%;
            left:35%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: darkgray;
            padding: 30px;
        }
        .login input{
            display: block;
            border-radius: 15px 50px;
            border: 2px solid darkblue;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .login a{
            color: rebeccapurple;
        }
        .button{
            border: 2px solid darkblue;
            background-color: lightseagreen;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login">
        <form action="login.php" method="post"> 
        <input type="email" name="email" placeholder="Enter your Name here!" required>
        <input type="password" name="password" placeholder="Enter Your Password here!" required>
        <input class="button" type="submit" name="submit" value="login" >
        <p>Don't register yet!<a href="register.php"> Sign Up</a></p>
    </form>
    </div>
</body>
</html>
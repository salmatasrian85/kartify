<?php
session_start(); 
if(isset($_SESSION['user_id'])) {
    if($_SESSION['user_role'] == "admin"){

    }else{
        echo "go for user dashboard";
    }
}else{
    header("Location: ../index.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            font-family: Arial, sans-serif;
        }
        .dashboard_sidebar{
            position: fixed;
            top: 0;
            left: 0;
            background-color: darkcyan;
            width: 220px;
            height: 100vh;
        }
        .dashboard_sidebar ul{
            padding: 20px 0;
        }
        .dashboard_sidebar ul li{
            list-style: none;
        }
        .dashboard_sidebar ul li a{
            display: block;
            text-decoration: none;
            color: white;
            padding: 12px;
            text-align: center;
            transition: 0.3s;
        }
        .dashboard_sidebar ul li a:hover{
            background-color: black;
        }
        .dashboard_main{
            margin-left: 240px; /* pushes content beside sidebar */
            padding: 40px;
        }
        .dashboard_main form{
            max-width: 400px;
        }
        .dashboard_main input,
        .dashboard_main textarea,
        .dashboard_main select{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .button{
            background-color: lightcoral;
            border: none;
            padding: 12px;
            border-radius: 20px;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
        .button:hover{
            background-color: crimson;
        }
    </style>
</head>
<body>
    <div class="dashboard_sidebar">
    <ul>
        <li><a href="addproduct.php">Add Product</a></li>
        <li><a href="">View Order</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
    </div>
    <div class="dashboard_main">
        <form action="addproduct.php" method="post"
        enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Enter Product Name here!">
            <textarea name="description" placeholder="Enter product description here!" rows="4"></textarea>
            <input type="number" name="price" placeholder="Enter price here!" >
            <input type="number" name="stock" placeholder="Enter stock number here!">
            <input type="file" name="image" >
            <select name="category_id">
            <option value="">Select Category ID</option>
            </select>
            <select name="category_name">
            <option value="">Select Category Name</option>
            </select>
            <input class="button" type="submit" name="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
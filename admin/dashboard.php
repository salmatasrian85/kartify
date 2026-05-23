<?php
session_start(); 
if(isset($_SESSION['user_id'])) {
    if($_SESSION['user_role'] == "admin"){

    }else{
       header("Location: ../dashboard.php");
    }
}else{
    header("Location: ../index.php");
}

include "../db.php";

$stats = [
    'products' => 0,
    'orders' => 0,
    'users' => 0,
    'stock_products' => 0,
];

$result = mysqli_query($conn, "SELECT COUNT(*) AS total_products FROM products");
if($result){ $row = mysqli_fetch_assoc($result); $stats['products'] = intval($row['total_products']); }

$result = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM single_order");
if($result){ $row = mysqli_fetch_assoc($result); $stats['orders'] = intval($row['total_orders']); }

$result = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
if($result){ $row = mysqli_fetch_assoc($result); $stats['users'] = intval($row['total_users']); }

$result = mysqli_query($conn, "SELECT COUNT(*) AS total_stock_products FROM products WHERE stock > 0");
if($result){ $row = mysqli_fetch_assoc($result); $stats['stock_products'] = intval($row['total_stock_products']); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

/* LAYOUT */
.container{
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#111;
    color:white;
    padding:30px;
    position:fixed;
}

.logo{
    font-size:24px;
    margin-bottom:40px;
    letter-spacing:2px;
}

.sidebar a{
    display:block;
    color:#bbb;
    text-decoration:none;
    margin:15px 0;
    transition:0.3s;
}

.sidebar a:hover{
    color:white;
}

/* MAIN */
.main{
    margin-left:240px;
    width:100%;
    background:#f5f6fa;
    min-height:100vh;
}

/* HEADER */
.header{
    padding:20px 30px;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.header h2{
    font-size:20px;
}

.user{
    font-size:14px;
    color:#555;
}

/* CONTENT */
.content{
    padding:30px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

.card h3{
    margin-bottom:10px;
}

.card p{
    color:#666;
    font-size:14px;
}

</style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">KARTIFY</div>
        <a class="active" href="dashboard.php">Dashboard</a>
        <a href="addproduct.php">Add Product</a>
        <a href="displayproduct.php">Manage Products</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="vieworder.php">Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <h2>Admin Dashboard</h2>
            <div class="user">
                <a href="admin_profile.php" style="margin-left:12px;display:inline-block;text-decoration:none;">
                    <span style="display:inline-block;width:34px;height:34px;border-radius:50%;background:#111;color:#fff;text-align:center;line-height:34px;font-weight:600;"><?php echo strtoupper(substr($_SESSION['user_name'],0,1)); ?></span>
                </a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="cards">

                <div class="card">
                    <h3>Total Products</h3>
                    <p><?php echo $stats['products']; ?> products are currently in your catalogue.</p>
                </div>

                <div class="card">
                    <h3>Total Orders</h3>
                    <p><?php echo $stats['orders']; ?> orders have been placed so far.</p>
                </div>

                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo $stats['users']; ?> users are registered in the system.</p>
                </div>

                <div class="card">
                    <h3>In-Stock Products</h3>
                    <p><?php echo $stats['stock_products']; ?> products currently have stock available.</p>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
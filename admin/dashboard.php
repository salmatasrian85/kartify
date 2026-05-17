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
        <a href="dashboard.php">Dashboard</a>
        <a href="addproduct.php">Add Product</a>
        <a href="displayproduct.php">Manage Products</a>
        <a href="vieworder.php">Orders</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <h2>Admin Dashboard</h2>
            <div class="user">
                Welcome, <?php echo $_SESSION['user_name']; ?>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="cards">

                <div class="card">
                    <h3>Products</h3>
                    <p>Manage all products in your store.</p>
                </div>

                <div class="card">
                    <h3>Orders</h3>
                    <p>View and manage customer orders.</p>
                </div>

                <div class="card">
                    <h3>Users</h3>
                    <p>Manage registered users.</p>
                </div>

                <div class="card">
                    <h3>Analytics</h3>
                    <p>Track performance and sales.</p>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
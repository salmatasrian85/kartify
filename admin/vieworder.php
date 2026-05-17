<?php
session_start();
include "../db.php"; // FIXED PATH

/* CHECK ADMIN LOGIN */
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['user_role'] != "admin"){
    echo "Access denied!";
    exit();
}

/* FETCH ALL ORDERS */
$sql = "SELECT * FROM single_order ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if(!$result){
    die("Database Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - View Orders</title>

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
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.header h2{
    font-size:20px;
}

/* CONTENT */
.content{
    padding:30px;
}

/* TABLE BOX */
.table-box{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    overflow-x:auto;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

th{
    background:#111;
    color:white;
    padding:12px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9f9f9;
}

/* BADGE STYLE */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    background:#eee;
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
            <h2>Customer Orders</h2>
            <div>Admin Panel</div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="table-box">

                <table>

                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Product ID</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while($row = mysqli_fetch_assoc($result)){ ?>

                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['product_id']; ?></td>
                            <td>৳ <?php echo $row['total_amount']; ?></td>
                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
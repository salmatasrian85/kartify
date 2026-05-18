<?php 
session_start();
include "../db.php";

/* CHECK ADMIN LOGIN */
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['user_role'] != "admin"){
    echo "Access denied!";
    exit();
}

/* FETCH ORDERS WITH USER INFO */
$sql = "SELECT single_order.*, users.name, users.email 
        FROM single_order 
        JOIN users ON single_order.user_id = users.id
        ORDER BY single_order.id DESC";

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
}

.sidebar a{
    display:block;
    color:#bbb;
    text-decoration:none;
    margin:15px 0;
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
}

/* CONTENT */
.content{
    padding:30px;
}

/* TABLE */
.table-box{
    background:white;
    padding:20px;
    border-radius:10px;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#111;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

/* STATUS BADGE */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    color:white;
}

.pending{ background:orange; }
.completed{ background:green; }
.cancelled{ background:red; }

/* ACTION */
.action a{
    padding:5px 10px;
    text-decoration:none;
    border-radius:5px;
    font-size:12px;
    color:white;
}

.view{ background:#3498db; }
.update{ background:#2ecc71; }
.delete{ background:#e74c3c; }

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

        <div class="header">
            <h2>Customer Orders</h2>
            <div>Admin Panel</div>
        </div>

        <div class="content">

            <div class="table-box">

                <table>

                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product ID</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while($row = mysqli_fetch_assoc($result)){ ?>

                    <tr>
                        <td>#<?php echo $row['id']; ?></td>

                        <td>
                            <?php echo $row['name']; ?><br>
                            <small><?php echo $row['email']; ?></small>
                        </td>

                        <td><?php echo $row['product_id']; ?></td>

                        <td>৳ <?php echo $row['total_amount']; ?></td>

                        <td>
                            <span class="badge <?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>

                        <td><?php echo $row['created_at']; ?></td>

                        <td class="action">
                            <a class="view" href="view_single_order.php?id=<?php echo $row['id']; ?>">View</a>
                            <a class="update" href="update_status.php?id=<?php echo $row['id']; ?>">Update</a>
                            <a class="delete" href="delete_order.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this order?')">Delete</a>
                        </td>
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
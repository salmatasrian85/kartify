<?php
session_start();
include "../db.php";

/* ================= AUTH CHECK ================= */
if(isset($_SESSION['user_id'])){

    if($_SESSION['user_role'] == "admin"){

        // ================= FETCH ORDERS =================
        $sql = "SELECT single_order.*, users.name, users.email
                FROM single_order
                JOIN users ON single_order.user_id = users.id
                ORDER BY single_order.id DESC";

        $result = mysqli_query($conn, $sql);

        if(!$result){
            die("Database Error: " . mysqli_error($conn));
        }

    } else {

        echo "Access denied!";
        exit();
    }

} else {

    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<title>View Orders</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

/* ================= RESET ================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

/* ================= LAYOUT ================= */
.container{
    display:flex;
}

/* ================= SIDEBAR ================= */
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
}

.sidebar a:hover{
    color:white;
}

/* ================= MAIN ================= */
.main{
    margin-left:240px;
    width:100%;
    background:#f5f6fa;
    min-height:100vh;
}

/* ================= HEADER ================= */
.header{
    padding:20px 30px;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

/* ================= CONTENT ================= */
.content{
    padding:30px;
}

/* ================= ALERTS ================= */
.success{
    background:#d4edda;
    color:#155724;
    padding:10px 12px;
    border-radius:6px;
    margin-bottom:15px;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px 12px;
    border-radius:6px;
    margin-bottom:15px;
}

/* ================= TABLE ================= */
.table-box{
    background:white;
    padding:20px;
    border-radius:10px;
    overflow-x:auto;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th{
    background:#111;
    color:white;
    padding:12px;
    text-align:left;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

/* ================= STATUS BADGE ================= */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    color:white;
}

.pending{
    background:orange;
}

.delivered,
.completed{
    background:green;
}

.cancelled{
    background:red;
}

/* ================= ACTION BUTTONS ================= */
.action a{
    display:inline-block;
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:12px;
    color:white;
    margin-right:5px;
}

.update{
    background:#2ecc71;
}

.delete{
    background:#e74c3c;
}

.page-title{
    font-size:20px;
    margin-bottom:18px;
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
        <a href="manage_users.php">Manage Users</a>
        <a href="vieworder.php">Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>

    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="header">

            <h2>Customer Orders</h2>

            <div>Admin Panel</div>

        </div>

        <div class="content">

            <h2 class="page-title">Order List</h2>

            <!-- STATUS MESSAGE -->
            <?php if(isset($_GET['msg'])){ ?>

                <?php if($_GET['msg'] == "deleted"){ ?>
                    <div class="success">Order deleted successfully!</div>
                <?php } ?>

                <?php if($_GET['msg'] == "updated"){ ?>
                    <div class="success">Order updated successfully!</div>
                <?php } ?>

                <?php if($_GET['msg'] == "error"){ ?>
                    <div class="error">Something went wrong!</div>
                <?php } ?>

            <?php } ?>

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
                                <?php echo htmlspecialchars($row['name']); ?><br>
                                <small><?php echo htmlspecialchars($row['email']); ?></small>
                            </td>

                            <td><?php echo $row['product_id']; ?></td>

                            <td>Tk <?php echo $row['total_amount']; ?></td>

                            <td>
                                <span class="badge <?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>

                            <td><?php echo $row['created_at']; ?></td>

                            <td class="action">

                                <a class="update" href="update_status.php?id=<?php echo $row['id']; ?>">
                                    Update
                                </a>

                                <a class="delete"
                                   href="delete_order.php?id=<?php echo $row['id']; ?>"
                                   onclick="return confirm('Delete this order?')">
                                    Delete
                                </a>

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
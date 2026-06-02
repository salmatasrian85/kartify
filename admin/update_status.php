<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: vieworder.php");
    exit();
}

$order_id = intval($_GET['id']);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $sql = "UPDATE single_order SET status = '$status' WHERE id = '$order_id'";
    if(mysqli_query($conn, $sql)){
        header("Location: vieworder.php?msg=updated");
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM single_order WHERE id = '$order_id'");
$order = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Order Status</title>
  <style>
    *{ margin:0; padding:0; box-sizing:border-box; font-family:Inter, sans-serif; }
    body{ background:#f5f6fa; }
    .container{ display:flex; }
    .sidebar{ width:240px; height:100vh; background:#111; color:white; padding:30px; position:fixed; }
    .logo{ font-size:24px; margin-bottom:40px; letter-spacing:2px; }
    .sidebar a{ display:block; color:#bbb; text-decoration:none; margin:15px 0; transition:0.3s; }
    .sidebar a:hover, .sidebar a.active{ color:white; }
    .main{ margin-left:240px; width:100%; background:#f5f6fa; min-height:100vh; }
    .header{ padding:20px 30px; background:white; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
    .header h2{ font-size:20px; }
    .content{ padding:30px; }
    .box{ background:#fff; padding:24px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); max-width:640px; }
    label{ display:block; margin-bottom:8px; font-weight:600; }
    select, button{ width:100%; padding:10px 14px; margin-top:8px; border:1px solid #ddd; border-radius:6px; }
    .actions{ margin-top:16px; display:flex; gap:12px; align-items:center; }
    .btn-primary{ background:#111; color:#fff; border:none; cursor:pointer; }
    .btn-link{ color:#111; text-decoration:none; padding:10px 14px; border:1px solid #ddd; border-radius:6px; display:inline-block; }
  
    
</style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="logo">KARTIFY</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="addproduct.php">Add Product</a>
        <a href="displayproduct.php">Manage Products</a>
        <a href="manage_users.php">Manage Users</a>
        <a class="active" href="vieworder.php">Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main">
        <div class="header">
            <h2>Update Order Status</h2>
            <div>Admin Panel</div>
        </div>
        <div class="content">
            <div class="box">
                <h2>Update Status for Order #<?php echo $order_id; ?></h2>
                <?php if(isset($error)){ echo '<div style="color:red; margin-top:12px;">'.htmlspecialchars($error).'</div>'; } ?>
                <form method="post">
                  <label for="status">Status</label>
                  <select name="status" id="status">
                    <option value="pending" <?php if(($order['status'] ?? '')=='pending') echo 'selected'; ?>>Pending</option>
                    <option value="delivered" <?php if(($order['status'] ?? '')=='delivered') echo 'selected'; ?>>Delivered</option>
                    <option value="cancelled" <?php if(($order['status'] ?? '')=='cancelled') echo 'selected'; ?>>Cancelled</option>
                  </select>
                  <div class="actions">
                    <button class="btn-primary" type="submit">Update</button>
                    <a class="btn-link" href="vieworder.php">Back</a>
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>

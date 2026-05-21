<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header('Location: ../index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = intval($_POST['user_id']);
    if($id > 0){
        mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    }
    header('Location: manage_users.php');
    exit();
}

// show confirmation
$uid = isset($_GET['user_id'])? intval($_GET['user_id']) : 0;
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$uid'");
$user = $res? mysqli_fetch_assoc($res) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Delete User</title>
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
.card{ background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); max-width:640px; }
.btn{ padding:10px 16px; border-radius:6px; border:none; cursor:pointer; }
.btn-del{ background:#e74c3c; color:#fff; }
.btn-cancel{ background:#ddd; margin-left:8px; text-decoration:none; color:#111; }
</style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="logo">KARTIFY</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="addproduct.php">Add Product</a>
        <a href="displayproduct.php">Manage Products</a>
        <a class="active" href="manage_users.php">Manage Users</a>
        <a href="vieworder.php">Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main">
        <div class="header">
            <h2>Delete User</h2>
            <div>Admin Panel</div>
        </div>
        <div class="content">
            <div class="card">
                <a class="btn btn-cancel" href="manage_users.php" style="margin-bottom:16px; display:inline-block;">← Back to Manage Users</a>
                <?php if(!$user){ echo '<p>User not found.</p>'; } else { ?>
                    <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($user['name']); ?></strong> (<?php echo htmlspecialchars($user['email']); ?>)? This action cannot be undone.</p>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo intval($user['id']); ?>">
                        <button class="btn btn-del" type="submit">Yes, Delete</button>
                        <a class="btn btn-cancel" href="manage_users.php">Cancel</a>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
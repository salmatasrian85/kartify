<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header('Location: ../index.php');
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Users</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
.toolbar{ display:flex; flex-wrap:wrap; gap:12px; margin-bottom:20px; }
.table{ width:100%; border-collapse:collapse; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,0.06); border-radius:8px; overflow:hidden; }
.table th, .table td{ padding:12px 14px; text-align:left; border-bottom:1px solid #eee; }
.actions a{ margin-right:8px; padding:6px 10px; text-decoration:none; border-radius:6px; }
.edit{ background:#111; color:#fff; }
.delete{ background:#e74c3c; color:#fff; }
.add{ background:#111; color:#fff; padding:8px 12px; text-decoration:none; border-radius:6px; }
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
            <h2>Manage Users</h2>
            <div>Admin Panel</div>
        </div>
        <div class="content">
            <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($u = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['phone']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td class="actions">
                    <a class="edit" href="edit_user.php?user_id=<?php echo $u['id']; ?>">Edit</a>
                    <a class="delete" href="delete_user.php?user_id=<?php echo $u['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
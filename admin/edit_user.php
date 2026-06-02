<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header('Location: ../index.php');
    exit();
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if($user_id <= 0){
    header('Location: manage_users.php');
    exit();
}

$msg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $role = mysqli_real_escape_string($conn, trim($_POST['role']));
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $checkSql = "SELECT id FROM users WHERE email='$email' AND id != '$user_id'";
    $checkRes = mysqli_query($conn, $checkSql);
    if(mysqli_num_rows($checkRes) > 0){
        $msg = 'Email already in use.';
    } else {
        $passwordSql = '';

        if(!empty($current_password) || !empty($new_password) || !empty($confirm_password)){
            if($current_password === ''){
                $msg = 'Current password is required to change the password.';
            } elseif($new_password === ''){
                $msg = 'New password cannot be empty.';
            } elseif($new_password !== $confirm_password){
                $msg = 'New password and confirm password do not match.';
            } else {
                $storedRes = mysqli_query($conn, "SELECT password FROM users WHERE id='$user_id'");
                $storedUser = mysqli_fetch_assoc($storedRes);
                $stored_password = $storedUser['password'] ?? '';

                if($current_password !== $stored_password){
                    $msg = 'Current password is incorrect.';
                } else {
                    $new_password = mysqli_real_escape_string($conn, $new_password);
                    $passwordSql = ", password='$new_password'";
                }
            }
        }

        if($msg === ''){
            $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', role='$role'$passwordSql WHERE id='$user_id'";
            if(mysqli_query($conn, $sql)){
                $msg = 'User updated successfully.';
            } else {
                $msg = 'DB Error: ' . mysqli_error($conn);
            }
        }
    }
}

$user = null;
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
if($res && mysqli_num_rows($res) > 0){
    $user = mysqli_fetch_assoc($res);
} else {
    header('Location: manage_users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit User</title>
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
.card{ background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); max-width:700px; }
.input, .textarea, .select{ width:100%; padding:10px; margin:8px 0 16px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
.textarea{ min-height:110px; resize:vertical; }
.btn{ padding:10px 16px; background:#111; color:#fff; border:none; border-radius:6px; cursor:pointer; }
.back-link{ display:inline-block; margin-bottom:18px; color:#111; text-decoration:none; }
.success{ margin-bottom:12px; color:#155724; background:#d4edda; padding:10px 12px; border-radius:6px; }
.error{ margin-bottom:12px; color:#721c24; background:#f8d7da; padding:10px 12px; border-radius:6px; }

    
</style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="logo">KARTIFY</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="addproduct.php">Add Product</a>
        <a href="displayproduct.php">Manage Products</a>
        <a href="manage_users.php" class="active">Manage Users</a>
        <a href="vieworder.php">Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main">
        <div class="header">
            <h2>Edit User</h2>
            <div>Admin Panel</div>
        </div>
        <div class="content">
            <div class="card">
                <?php if($msg): ?>
                    <div class="<?php echo strpos($msg, 'Error') === 0 ? 'error' : 'success'; ?>"><?php echo htmlspecialchars($msg); ?></div>
                <?php endif; ?>
                <form method="post">
                    <label>Full Name</label>
                    <input class="input" type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>">
                    <label>Email</label>
                    <input class="input" type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                    <label>Phone</label>
                    <input class="input" type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    <label>Address</label>
                    <textarea class="textarea" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    <label>Role</label>
                    <select class="select" name="role" required>
                        <option value="user" <?php echo ($user['role'] === 'user' ? 'selected' : ''); ?>>User</option>
                        <option value="admin" <?php echo ($user['role'] === 'admin' ? 'selected' : ''); ?>>Admin</option>
                    </select>
                    <label>Current Password</label>
                    <input class="input" type="password" name="current_password">
                    <label>New Password</label>
                    <input class="input" type="password" name="new_password">
                    <label>Confirm New Password</label>
                    <input class="input" type="password" name="confirm_password">
                    <button class="btn" type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header('Location: ../index.php');
    exit();
}

$msg = "";
$user_id = intval($_SESSION['user_id']);

// If saving profile updates
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = $_POST['password'];

    // Check email uniqueness for other users
    $checkSql = "SELECT id FROM users WHERE email='$email' AND id != '$user_id'";
    $checkRes = mysqli_query($conn, $checkSql);
    if(mysqli_num_rows($checkRes) > 0){
        $msg = "Email already in use.";
    } else {
        if(trim($password) !== ''){
            $password = mysqli_real_escape_string($conn, $password);
            $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', password='$password' WHERE id='$user_id'";
        } else {
            $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";
        }

        if(mysqli_query($conn, $sql)){
            $msg = 'Profile updated successfully.';
        } else {
            $msg = 'DB Error: ' . $conn->error;
        }
    }
}

// Load current admin data
$user = ['id'=>$user_id,'name'=>'','email'=>'','phone'=>'','address'=>'','role'=>'admin'];
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
if($res && mysqli_num_rows($res) > 0){ $user = mysqli_fetch_assoc($res); }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Profile</title>
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
.input{ width:100%; padding:10px; margin:8px 0; border:1px solid #ddd; border-radius:6px; }
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
        <a href="manage_users.php">Manage Users</a>
        <a href="vieworder.php">Orders</a>
        <a href="admin_profile.php" class="active">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main">
        <div class="header">
            <h2>Admin Profile</h2>
            <div>Admin Panel</div>
        </div>
        <div class="content">
            <div class="card">
                <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
                <h2>Profile Settings</h2>
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
                    <textarea class="input" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>

                    <label>Password (leave blank to keep current)</label>
                    <input class="input" type="password" name="password">

                    <button class="btn" type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
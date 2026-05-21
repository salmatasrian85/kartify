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
body{ font-family:Inter, sans-serif; background:#f5f6fa; }
.container{ max-width:640px; margin:30px auto; padding:20px; }
.card{ background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
.btn{ padding:10px 16px; border-radius:6px; border:none; cursor:pointer; }
.btn-del{ background:#e74c3c; color:#fff; }
.btn-cancel{ background:#ddd; margin-left:8px; }
</style>
</head>
<body>
<div class="container">
    <a href="manage_users.php">← Back to Manage Users</a>
    <div class="card">
        <h2>Delete User</h2>
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
</body>
</html>
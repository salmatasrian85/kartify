<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = $_POST['password']; // plain, stored as-is to match simple existing logic

    // If email is changed, ensure it's not used by another user
    $checkSql = "SELECT id FROM users WHERE email='$email' AND id != '$user_id'";
    $checkRes = mysqli_query($conn, $checkSql);
    if(mysqli_num_rows($checkRes) > 0){
        $msg = "Email already taken by another account.";
    } else {
        if(trim($password) !== ''){
            $password = mysqli_real_escape_string($conn, $password);
            $update = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', password='$password' WHERE id='$user_id'";
        } else {
            $update = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";
        }

        if(mysqli_query($conn, $update)){
            $msg = "Profile updated successfully.";
            $_SESSION['user_name'] = $name;
        } else {
            $msg = "Error updating profile: " . $conn->error;
        }
    }
}

// fetch current data
$res = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($res);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile</title>
<style>
*{ box-sizing:border-box; font-family:Inter, sans-serif; }
.container{ max-width:840px; margin:40px auto; padding:20px; }
.card{ background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
.input{ width:100%; padding:10px; margin:8px 0; border:1px solid #ddd; border-radius:6px; }
.btn{ padding:10px 16px; background:#111; color:#fff; border:none; border-radius:6px; cursor:pointer; }
.msg{ margin:10px 0; color:green; }
</style>
</head>
<body>

<div class="container">
    <a href="index.php">← Back to Shop</a>
    <div class="card">
        <h2>My Profile</h2>
        <?php if($msg !== ""){ echo '<div class="msg">'.htmlspecialchars($msg).'</div>'; } ?>
        <form method="post">
            <label>Full Name</label>
            <input class="input" type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>">

            <label>Email</label>
            <input class="input" type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">

            <label>Phone</label>
            <input class="input" type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label>Address</label>
            <textarea class="input" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>

            <label>New Password (leave blank to keep current)</label>
            <input class="input" type="password" name="password">

            <button class="btn" type="submit">Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
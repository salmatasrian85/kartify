<?php
session_start();
include "../db.php";

$msg = "";

if(isset($_SESSION['user_id'])){

    if($_SESSION['user_role'] == "admin"){

        $user_id = intval($_SESSION['user_id']);

        // ================= UPDATE PROFILE =================
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);

            $current_password = trim($_POST['current_password'] ?? '');
            $new_password = trim($_POST['new_password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            // ================= GET CURRENT PASSWORD =================
            $storedRes = mysqli_query($conn, "SELECT password FROM users WHERE id='$user_id'");
            $storedUser = mysqli_fetch_assoc($storedRes);

            $stored_password = $storedUser['password'];

            // ================= CHECK EMAIL =================
            $checkSql = "SELECT id FROM users 
                         WHERE email='$email' 
                         AND id != '$user_id'";

            $checkRes = mysqli_query($conn, $checkSql);

            if(mysqli_num_rows($checkRes) > 0){

                $msg = "Email already exists!";

            } else {

                $passwordSql = "";

                // ================= PASSWORD CHANGE =================
                if(
                    !empty($current_password) ||
                    !empty($new_password) ||
                    !empty($confirm_password)
                ){

                    if(empty($current_password)){

                        $msg = "Enter current password!";

                    } elseif($current_password != $stored_password){

                        $msg = "Current password incorrect!";

                    } elseif(empty($new_password)){

                        $msg = "Enter new password!";

                    } elseif($new_password != $confirm_password){

                        $msg = "Passwords do not match!";

                    } else {

                        $passwordSql = ", password='$new_password'";
                    }
                }

                // ================= UPDATE QUERY =================
                if(empty($msg)){

                    $sql = "UPDATE users SET
                            name='$name',
                            email='$email',
                            phone='$phone',
                            address='$address'
                            $passwordSql
                            WHERE id='$user_id'";

                    $result = mysqli_query($conn, $sql);

                    if(!$result){

                        $msg = "Error!: {$conn->error}";

                    } else {

                        $msg = "Profile updated successfully!";
                    }
                }
            }
        }

        // ================= FETCH USER =================
        $sql_user = "SELECT * FROM users WHERE id='$user_id'";
        $result_user = mysqli_query($conn, $sql_user);

        $user = mysqli_fetch_assoc($result_user);

    } else {

        echo "go for user dashboard";
        exit();
    }

} else {

    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<title>Admin Profile</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

.container{
    display:flex;
}

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

.main{
    margin-left:240px;
    width:100%;
    background:#f5f6fa;
    min-height:100vh;
}

.header{
    padding:20px 30px;
    background:white;
    display:flex;
    justify-content:space-between;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.content{
    padding:30px;
    display:flex;
    justify-content:center;
}

.form-box{
    background:white;
    padding:35px;
    width:500px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

input, textarea{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ddd;
}

.btn{
    width:100%;
    padding:12px;
    background:#111;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.msg{
    margin-bottom:15px;
    color:green;
    font-weight:500;
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

            <h2>Admin Profile</h2>

            <div>Admin Panel</div>

        </div>

        <div class="content">

            <div class="form-box">

                <h2 style="margin-bottom:20px;">Profile Settings</h2>

                <!-- MESSAGE -->
                <?php if(!empty($msg)){ ?>
                    <div class="msg">
                        <?php echo $msg; ?>
                    </div>
                <?php } ?>

                <form method="post">

                    <label>Full Name</label>
                    <input 
                        type="text" 
                        name="name"
                        value="<?php echo htmlspecialchars($user['name']); ?>"
                        required
                    >

                    <label>Email</label>
                    <input 
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        required
                    >

                    <label>Phone</label>
                    <input 
                        type="text"
                        name="phone"
                        value="<?php echo htmlspecialchars($user['phone']); ?>"
                    >

                    <label>Address</label>
                    <textarea name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>

                    <label>Current Password</label>
                    <input type="password" name="current_password">

                    <label>New Password</label>
                    <input type="password" name="new_password">

                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password">

                    <button class="btn" type="submit">
                        Save Changes
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
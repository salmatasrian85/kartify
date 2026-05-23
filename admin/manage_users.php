<?php
session_start();
include "../db.php";

if(isset($_SESSION['user_id'])){

    if($_SESSION['user_role'] == "admin"){

        // ================= FETCH USERS =================
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

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

<title>Manage Users</title>

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
}

.table-box{
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:14px;
    text-align:left;
    border-bottom:1px solid #eee;
    font-size:14px;
}

th{
    background:#f9f9f9;
    font-weight:600;
}

.actions a{
    display:inline-block;
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
    margin-right:6px;
}

.edit{
    background:#111;
    color:#fff;
}

.delete{
    background:#e74c3c;
    color:#fff;
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

            <h2>Manage Users</h2>

            <div>Admin Panel</div>

        </div>

        <div class="content">

            <div class="table-box">

                <table>

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

                                <a class="edit" href="edit_user.php?user_id=<?php echo $u['id']; ?>">
                                    Edit
                                </a>

                                <a class="delete" href="delete_user.php?user_id=<?php echo $u['id']; ?>">
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
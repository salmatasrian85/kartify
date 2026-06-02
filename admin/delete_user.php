<?php
session_start();
include "../db.php";

if(isset($_SESSION['user_id'])){

    if($_SESSION['user_role'] == "admin"){

        // ================= DELETE USER =================
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = intval($_POST['user_id']);

            if($id > 0){

                mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
            }

            header('Location: manage_users.php');
            exit();
        }

        // ================= FETCH USER =================
        $uid = isset($_GET['user_id']) 
            ? intval($_GET['user_id']) 
            : 0;

        $sql = "SELECT * FROM users WHERE id='$uid'";
        $result = mysqli_query($conn, $sql);

        $user = mysqli_fetch_assoc($result);

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

<title>Delete User</title>

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

.text{
    margin:20px 0;
    line-height:1.8;
    color:#444;
}

.btn-group{
    display:flex;
    gap:10px;
    margin-top:20px;
}

.btn{
    padding:12px 20px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    text-decoration:none;
    display:inline-block;
    font-size:14px;
}

.delete-btn{
    background:#111;
    color:white;
}

.cancel-btn{
    background:#ddd;
    color:#111;
}

.back-btn{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#111;
    font-size:14px;
}

.not-found{
    color:red;
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

            <h2>Delete User</h2>

            <div>Admin Panel</div>

        </div>

        <div class="content">

            <div class="form-box">
                <?php if(!$user){ ?>

                    <div class="not-found">
                        User not found!
                    </div>

                <?php } else { ?>

                    <h2>Delete Confirmation</h2>

                    <div class="text">
                        Are you sure you want to delete 
                        <strong>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </strong>
                        (<?php echo htmlspecialchars($user['email']); ?>)?
                        <br><br>
                        This action cannot be undone.
                    </div>

                    <form method="post">

                        <input 
                            type="hidden" 
                            name="user_id" 
                            value="<?php echo intval($user['id']); ?>"
                        >

                        <div class="btn-group">

                            <button 
                                class="btn delete-btn" 
                                type="submit"
                            >
                                Yes, Delete
                            </button>

                            <a 
                                class="btn cancel-btn" 
                                href="manage_users.php"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                <?php } ?>

            </div>

        </div>

    </div>

</div>


</body>
</html>
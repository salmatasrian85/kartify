<?php
session_start(); 
include "../db.php";

$sql = "select * from products";
$result = mysqli_query($conn,$sql);

if(isset($_SESSION['user_id'])){ 
    if($_SESSION['user_role'] == "admin"){

        if(!$result){
            echo "Error!: {$conn->error}";
        }

    }else{
        echo "go for user dashboard";
        exit();
    }

}else{
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Products</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

/* LAYOUT */
.container{
    display:flex;
}

/* SIDEBAR */
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
    transition:0.3s;
}

.sidebar a:hover{
    color:white;
}

/* MAIN */
.main{
    margin-left:240px;
    width:100%;
    background:#f5f6fa;
    min-height:100vh;
}

/* HEADER */
.header{
    padding:20px 30px;
    background:white;
    display:flex;
    justify-content:space-between;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.header h2{
    font-size:20px;
}

/* CONTENT */
.content{
    padding:30px;
}

/* TABLE CARD */
.table-box{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    overflow-x:auto;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

th{
    background:#111;
    color:white;
    padding:12px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9f9f9;
}

/* IMAGE */
img{
    width:80px;
    border-radius:6px;
}

/* BUTTONS */
a.update{
    padding:6px 10px;
    background:#4CAF50;
    color:white;
    border-radius:5px;
    text-decoration:none;
    font-size:13px;
}

a.delete{
    padding:6px 10px;
    background:#e74c3c;
    color:white;
    border-radius:5px;
    text-decoration:none;
    font-size:13px;
}

a.update:hover{ 
    background:#3e8e41; 
}
a.delete:hover{ 
    background:#c0392b; 
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
        <a class="active" href="displayproduct.php">Manage Products</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="vieworder.php">Customer Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <h2>All Products</h2>
            <div>Admin Panel</div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="table-box">

                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    
                    <tbody>
                        <?php while($row=mysqli_fetch_assoc($result)){ ?>
                        <tr>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo $row['price'] ?></td>
                            <td><?php echo $row['stock'] ?></td>
                            <td>
                                <img src="../image/<?php echo $row['image'] ?>" alt="">
                            </td>
                            <td><?php echo $row['category_name'] ?></td>
                            <td>
                                <a class="update" href="updateproduct.php?product_id=<?php echo $row['id'] ?>">
                                    Update
                                </a>
                            </td>
                            <td>
                                <a class="delete" href="deleteproduct.php?product_id=<?php echo $row['id'] ?>">
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
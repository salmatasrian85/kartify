<?php
session_start();
include "../db.php";

$row = null;

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_role'] == "admin"){

        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];

            $sql = "SELECT * FROM products WHERE id = '$product_id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
        }

        if(isset($_POST['update'])){
            $product_id = $_POST['product_id'];

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $image = $_FILES['image']['name'];
            $tmp_location = $_FILES['image']['tmp_name'];

            if($image == ""){
                $sql2 = "UPDATE products SET 
                        name='$name',
                        description='$description',
                        price='$price',
                        stock='$stock'
                        WHERE id='$product_id'";
            } else {
                $upload_location = "../image/";
                move_uploaded_file($tmp_location, $upload_location.$image);

                $sql2 = "UPDATE products SET 
                        name='$name',
                        description='$description',
                        price='$price',
                        stock='$stock',
                        image='$image'
                        WHERE id='$product_id'";
            }

            $result2 = mysqli_query($conn, $sql2);

            if(!$result2){
                echo "Error: {$conn->error}";
            }else{
                header("Location: displayproduct.php");
                exit();
            }
        }

    }else{
        echo "Go to user dashboard";
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
<title>Update Product</title>

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

/* FORM CARD */
.form-box{
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    width:500px;
}

/* LABEL */
label{
    display:block;
    margin-top:12px;
    font-size:14px;
    font-weight:500;
    color:#333;
}

/* INPUTS */
input, textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    border-radius:6px;
    border:1px solid #ddd;
}

input:focus, textarea:focus{
    outline:none;
    border-color:#111;
}

/* BUTTON */
.btn{
    background:#111;
    color:white;
    border:none;
    padding:12px;
    border-radius:6px;
    cursor:pointer;
    width:100%;
    margin-top:15px;
}

.btn:hover{
    background:#333;
}

/* IMAGE BOX */
.image-box{
    margin:10px 0 15px 0;
    padding:10px;
    border:1px dashed #ccc;
    border-radius:8px;
    text-align:center;
    background:#fafafa;
}

.image-box img{
    width:120px;
    border-radius:8px;
}

.no-img{
    color:#888;
    font-size:13px;
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
            <h2>Update Product</h2>
            <div>Admin Panel</div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="form-box">

                <form method="post" enctype="multipart/form-data">

                    <input type="hidden" name="product_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">

                    <label>Product Name</label>
                    <input type="text" name="name" placeholder="Enter product name"
                    value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>" required>

                    <label>Description</label>
                    <textarea name="description" placeholder="Enter product description"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>

                    <label>Price</label>
                    <input type="number" name="price" placeholder="Enter price"
                    value="<?php echo isset($row['price']) ? $row['price'] : ''; ?>" required>

                    <label>Stock</label>
                    <input type="number" name="stock" placeholder="Enter stock quantity"
                    value="<?php echo isset($row['stock']) ? $row['stock'] : ''; ?>" required>

                    <label>Current Image</label>
                    <div class="image-box">
                        <?php if(isset($row['image']) && $row['image'] != ""){ ?>
                            <img src="../image/<?php echo $row['image']; ?>">
                        <?php } else { ?>
                            <p class="no-img">No image available</p>
                        <?php } ?>
                    </div>

                    <label>Upload New Image</label>
                    <input type="file" name="image">

                    <input type="submit" name="update" value="Update Product" class="btn">

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
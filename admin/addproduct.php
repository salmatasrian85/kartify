<?php 
session_start(); 
include "../db.php";

$msg = "";

if(isset($_SESSION['user_id'])){

    if($_SESSION['user_role'] == "admin"){

        $sql1 = "SELECT * FROM categories";
        $result1 = mysqli_query($conn,$sql1);

        if(isset($_POST['submit'])){

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $category_name = $_POST['category_name'];
            $new_category = $_POST['new_category'];

            // ================= CATEGORY LOGIC =================
            if(!empty($new_category)){
                $category_name = $new_category;

                // check if category exists
                $check = "SELECT * FROM categories WHERE name='$new_category'";
                $check_result = mysqli_query($conn, $check);

                if(mysqli_num_rows($check_result) > 0){
                    $msg = "Category already exists!";
                } else {
                    $insert_cat = "INSERT INTO categories (name) VALUES ('$new_category')";
                    mysqli_query($conn, $insert_cat);
                    $msg = "New category added!";
                }
            }

            // ================= IMAGE UPLOAD =================
            $image = $_FILES['image']['name'];
            $tmp_location = $_FILES['image']['tmp_name'];
            $upload_location = "../image/";

            // ================= PRODUCT INSERT =================
            $sql = "INSERT INTO products 
                (name, description, price, stock, image, category_name)
                VALUES
                ('$name', '$description', '$price', '$stock', '$image', '$category_name')";

            $result = mysqli_query($conn, $sql);

            if(!$result){
                echo "Error!: {$conn->error}";
            }else{
                move_uploaded_file($tmp_location,$upload_location.$image);
                $msg .= " Product added successfully!";
            }
        }

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
<title>Add Product</title>

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
    width:450px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

input, textarea, select{
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

.or-text{
    text-align:center;
    color:#888;
    margin:10px 0;
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
        <a class="active" href="addproduct.php">Add Product</a>
        <a href="displayproduct.php">Manage Products</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="vieworder.php">Orders</a>
        <a href="admin_profile.php">Profile</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="header">
            <h2>Add Product</h2>
            <div>Admin Panel</div>
        </div>

        <div class="content">

            <div class="form-box">

                <h2>Create New Product</h2>

                <!-- MESSAGE -->
                <?php if(!empty($msg)) { ?>
                    <div class="msg"><?php echo $msg; ?></div>
                <?php } ?>

                <form method="post" enctype="multipart/form-data">

                <label>Product Name</label>
                <input type="text" name="name" required>

                <label>Description</label>
                <textarea name="description"></textarea>

                <label>Price</label>
                <input type="number" name="price">

                <label>Stock</label>
                <input type="number" name="stock">

                <label>Product Image</label>
                <input type="file" name="image">

                <label>Select Category</label>
                <select name="category_name">
                    <option value="">Choose existing category</option>
                    <?php while($row = mysqli_fetch_assoc($result1)){ ?>
                        <option value="<?php echo $row['name']; ?>">
                            <?php echo $row['name']; ?>
                        </option>
                    <?php } ?>
                </select>
                <label>Add New Category</label>
                <input type="text" name="new_category">

                <button class="btn" type="submit" name="submit">
                    Add Product
                </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>
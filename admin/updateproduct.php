<?php
session_start();
include "../db.php";

$row = null;

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_role'] == "admin"){

        // First load: get product data
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];

            $sql = "SELECT * FROM products WHERE id = '$product_id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
        }

        // Update
        if(isset($_POST['update'])){
            $product_id = $_POST['product_id'];

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $image = $_FILES['image']['name'];
            $tmp_location = $_FILES['image']['tmp_name'];

            if($image == ""){
                // No new image
                $sql2 = "UPDATE products SET 
                        name='$name',
                        description='$description',
                        price='$price',
                        stock='$stock'
                        WHERE id='$product_id'";
            } else {
                // New image
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
                // Redirect to view page
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
    <title>Update Product</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
        }

        /* Sidebar */
        .sidebar{
            width: 220px;
            height: 100vh;
            background: darkcyan;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar ul{
            list-style: none;
            padding: 20px 0;
        }

        .sidebar ul li a{
            display: block;
            padding: 12px;
            text-decoration: none;
            color: white;
            text-align: center;
        }

        .sidebar ul li a:hover{
            background: black;
        }

        /* Main */
        .main{
            margin-left: 240px;
            padding: 40px;
        }

        .form-box{
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2{
            margin-bottom: 20px;
            text-align: center;
        }

        input, textarea{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="file"]{
            border: none;
        }

        .btn{
            background: lightcoral;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }

        .btn:hover{
            background: crimson;
        }

        img{
            margin-top: 10px;
            border-radius: 8px;
        }

    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <ul>
        <li><a href="addproduct.php">Add Product</a></li>
        <li><a href="displayproduct.php">View Product</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main -->
<div class="main">
    <div class="form-box">

        <h2>Update Product</h2>
        
        <form method="post" enctype="multipart/form-data">

            <!-- hidden id -->
            <input type="hidden" name="product_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">

            <input type="text" name="name" placeholder="Product Name"
            value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>" required>

            <textarea name="description" placeholder="Description"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>

            <input type="number" name="price" placeholder="Price"
            value="<?php echo isset($row['price']) ? $row['price'] : ''; ?>" required>

            <input type="number" name="stock" placeholder="Stock"
            value="<?php echo isset($row['stock']) ? $row['stock'] : ''; ?>" required>

            <p>Current Image:</p>

            <?php if(isset($row['image']) && $row['image'] != ""){ ?>
                <img src="../image/<?php echo $row['image']; ?>" width="100">
            <?php } ?>

            <input type="file" name="image">

            <input type="submit" name="update" value="Update Product" class="btn">

        </form>

    </div>
</div>

</body>
</html>
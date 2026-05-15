<?php
session_start(); 
include "../db.php";

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

            if(!empty($new_category)){
            $category_name = $new_category;

            // check if category already exists
            $check = "SELECT * FROM categories WHERE name='$new_category'";
            $check_result = mysqli_query($conn, $check);

            if(mysqli_num_rows($check_result) == 0){
                // insert only if not exists
                $insert_cat = "INSERT INTO categories (name) VALUES ('$new_category')";
                mysqli_query($conn, $insert_cat);
            }
        }

            $image = $_FILES['image']['name'];
            $tmp_location = $_FILES['image']['tmp_name'];

            $upload_location= "../image/";

            $sql = "INSERT INTO products 
                (name, description, price, stock, image, category_name)
                VALUES
                ('$name', '$description', '$price', '$stock', '$image', '$category_name')";

            $result = mysqli_query($conn, $sql);

            if(!$result){
                echo "Error!: {$conn->error}";
            }else{
                move_uploaded_file($tmp_location,$upload_location.$image);
                echo "Product added successfully!";
            }
        }

    }else{
        echo "go for user dashboard";
    }

}else{
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Product</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            font-family: Arial;
            background: #f4f6f8;
        }

        .sidebar{
            width: 220px;
            height: 100vh;
            background: darkcyan;
            position: fixed;
        }

        .sidebar ul{
            padding: 20px 0;
        }

        .sidebar ul li{
            list-style: none;
        }

        .sidebar ul li a{
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            text-align: center;
        }

        .sidebar ul li a:hover{
            background: black;
        }

        .main{
            margin-left: 240px;
            padding: 40px;
        }

        .form-box{
            background: white;
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input, textarea, select{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn{
            background: lightcoral;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover{
            background: crimson;
        }

        .or-text{
            text-align: center;
            color: gray;
            font-size: 14px;
        }

    </style>
</head>
<body>

<div class="sidebar">
    <ul>
        <li><a href="addproduct.php">Add Product</a></li>
        <li><a href="displayproduct.php">View Product</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="form-box">

        <h2>Add Product</h2>

        <form method="post" enctype="multipart/form-data">

            <input type="text" name="name" placeholder="Product Name" required>

            <textarea name="description" placeholder="Description"></textarea>

            <input type="number" name="price" placeholder="Price">

            <input type="number" name="stock" placeholder="Stock">

            <input type="file" name="image">

            <select name="category_name">
                <option value="">Select Existing Category</option>
                <?php while($row = mysqli_fetch_assoc($result1)){ ?>
                    <option value="<?php echo $row['name']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
                <?php } ?>
            </select>

            <p class="or-text">OR</p>

            
            <input type="text" name="new_category" placeholder="Add New Category">

            <input type="submit" name="submit" value="Add Product" class="btn">

        </form>

    </div>
</div>

</body>
</html>
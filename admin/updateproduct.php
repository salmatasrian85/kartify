<?php
session_start(); 
include "../db.php";

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_role'] == "admin"){
        $sql1 = "select * from categories";
        $result1 = mysqli_query($conn,$sql1);
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];
            $sql2 = "select * from products where id ='$product_id'";
            $result2 = mysqli_query($conn,$sql2);
            $row2 = mysqli_fetch_assoc($result2);
            }
       
        if(isset($_POST['submit'])){

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $category_name = $_POST['category_name'];

            $image = $_FILES['image']['name'];
            $tmp_location = $_FILES['image']['tmp_name'];

            $upload_location= "../image/";

            $sql = "insert into products 
                (name, description, price, stock, image, category_name)
                values
                ('$name', '$description', '$price', '$stock', '$image', '$category_name')";

            $result = mysqli_query($conn, $sql);

            if(!$result){
                echo "Error!: {$conn->error}";
            }else{
                echo "Product added successfully!";
                move_uploaded_file($tmp_location,$upload_location.$image);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            font-family: Arial, sans-serif;
        }
        .dashboard_sidebar{
            position: fixed;
            top: 0;
            left: 0;
            background-color: darkcyan;
            width: 220px;
            height: 100vh;
        }
        .dashboard_sidebar ul{
            padding: 20px 0;
        }
        .dashboard_sidebar ul li{
            list-style: none;
        }
        .dashboard_sidebar ul li a{
            display: block;
            text-decoration: none;
            color: white;
            padding: 12px;
            text-align: center;
            transition: 0.3s;
        }
        .dashboard_sidebar ul li a:hover{
            background-color: black;
        }
        .dashboard_main{
            margin-left: 240px; /* pushes content beside sidebar */
            padding: 40px;
        }
        .dashboard_main form{
            max-width: 400px;
        }
        .dashboard_main input,
        .dashboard_main textarea,
        .dashboard_main select{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .button{
            background-color: lightcoral;
            border: none;
            padding: 12px;
            border-radius: 20px;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
        .button:hover{
            background-color: crimson;
        }
    </style>
</head>
<body>
    <div class="dashboard_sidebar">
    <ul>
        <li><a href="updateproduct.php">Add Product</a></li>
        <li><a href="displayproduct.php">View Order</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
    </div>
    <div class="dashboard_main">
        <form action="updateproduct.php?product_id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo $row2['name']; ?>">
            <textarea name="description" >
            <?php echo $row2['description']; ?>
            </textarea>
            <input type="number" name="price" value="<?php echo $row2['price']; ?>" >
            <input type="number" name="stock" value="<?php echo $row2['stock']; ?>">
            <img src="../image/<?php echo $row2['name']; ?>" width="100">
            <input type="file" name="image" >
             <select name="category_name">
                <h1>Category name is:<?php echo $row2['category_name']; ?></h1>
                <option value="">Select Category</option>
                <?php while($row = mysqli_fetch_assoc($result1)){ ?>
                    <option value="<?php echo $row['name']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
                <?php } ?>
            </select>
            <input class="button" type="submit" name="submit" value="Update Product">
        </form>
    </div>
</body>
</html>
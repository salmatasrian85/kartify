<?php
session_start();
include "../db.php";

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_role'] == "admin"){

        // Get product id from URL
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];

            // Fetch existing product data
            $sql = "SELECT * FROM products WHERE id = '$product_id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
        }

        // Update data
        if(isset($_POST['update'])){
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            // Image handling
            $image = $_FILES['image']['name'];
            $tmp_location = $_FILES['image']['tmp_name'];

            if($image == ""){
                // If no new image uploaded
                $sql2 = "UPDATE products SET 
                        name='$name',
                        description='$description',
                        price='$price',
                        stock='$stock'
                        WHERE id='$product_id'";
            } else {
                // If new image uploaded
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
                echo "Product updated successfully!";
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
</head>
<body>

<h2>Update Product</h2>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" value="<?php echo $row['name']; ?>"><br><br>

    <textarea name="description"><?php echo $row['description']; ?></textarea><br><br>

    <input type="number" name="price" value="<?php echo $row['price']; ?>"><br><br>

    <input type="number" name="stock" value="<?php echo $row['stock']; ?>"><br><br>

    <p>Current Image:</p>
    <img src="../image/<?php echo $row['image']; ?>" width="100"><br><br>

    <input type="file" name="image"><br><br>

    <input type="submit" name="update" value="Update Product">
</form>

</body>
</html>
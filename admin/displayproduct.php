<?php
session_start(); 
include "../db.php";

if(isset($_SESSION['user_id'])){
    $sql = "select * from products";
    $result = mysqli_query($conn,$sql);

    if($_SESSION['user_role'] == "admin"){
            $result = mysqli_query($conn, $sql);

            if(!$result){
                echo "Error!: {$conn->error}";
            }else{
               
            }
    }else{
        echo "go for user dashboard";
    }

}else{
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Layout */
        .container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 200px;
            background-color: #0f8a8a;
            color: white;
            height: 100vh;
            padding: 20px;
        }

        .sidebar h3, .sidebar p {
            margin: 20px 0;
            cursor: pointer;
        }

        /* Content */
        .content {
            flex: 1;
            padding: 20px;
            background-color: #d0e4ea;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #b7d3db;
        }

        thead {
            background-color: white;
        }

        th, td {
            border: 2px solid #4b6cb7;
            padding: 10px;
            text-align: center;
        }

        /* Description column */
        .description {
            text-align: justify;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Image */
        img {
            width: 120px;
            border-radius: 8px;
        }

        /* Buttons */
        button {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .update {
            background-color: lightgreen;
        }

        .delete {
            background-color: #ff7b7b;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Add Product</h3>
        <p>View Order</p>
        <p>Logout</p>
    </div>

    <!-- Main Content -->
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Product Title</th>
                    <th>Product Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Category Name</th>
                    <th>Action</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row=mysqli_fetch_assoc($result)){

                ?>
                <tr>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['description']?></td>
                    <td><?php echo $row['price']?></td>
                    <td><?php echo $row['stock']?></td>
                    <td><img src="../image/<?php echo $row['image']?>" alt=""></td>
                    <td><?php echo $row['category_name']?></td>
                    <td><a href="update">Update</a></td>
                    <td><a href="delete">Delete</a></td>
                </tr>
                <?php } ?> 
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
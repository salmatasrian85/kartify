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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        .dashboard_sidebar{
            position: fixed;
            top: 0;
            background-color: darkcyan;
            width: 200px;
            height: 100%;
        }
        .dashboard_sidebar ul li{
            list-style: none;
            text-align: center;
            
        }
        .dashboard_sidebar ul li a{
            display: block;
            text-decoration: none;
            color: white;
            padding: 10px;
        }
       .dashboard_sidebar ul li a:hover{
        background-color: black;
       }
       .dashboard_main{
        padding: 30px;
        margin-left: 200px;
       }
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
    <div class="dashboard_sidebar">
    <ul>
        <li><a href="addproduct.php">Add Product</a></li>
        <li><a href="displayproduct.php">View Order</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
    </div>
    <div class="dashboard_main">

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
                    <td><a class="update" href="">Update</a></td>
                    <td><a class ="delete" href="">Delete</a></td>
                </tr>
                <?php } ?> 
            </tbody>
        </table>
    </div>
</div>
    </div>
</body>
</html>

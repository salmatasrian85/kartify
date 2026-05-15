<?php
session_start();
include "db.php";

/* ALL PRODUCTS BY DEFAULT */
$sql_product_category = "SELECT * FROM products";

/* IF CATEGORY CLICKED */
if(isset($_GET['category_name']) && $_GET['category_name'] != ""){
    $category_name = mysqli_real_escape_string($conn, $_GET['category_name']);

    $sql_product_category = "SELECT * FROM products 
    WHERE category_name = '$category_name'";
}

$result_product_category = mysqli_query($conn, $sql_product_category);

/* CATEGORY LIST */
$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($conn, $sql_category);
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
            overflow-x: hidden;
        }

        .header{
            position:fixed;
            top: 0;
            width: 100%;
            background-color: gray;
            display:flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px;
        }

        .header ui li{
            list-style: none;
        }

        .header a{
            text-decoration:none;
            color: white;
        }

        .header li{
            display: inline-block;
            margin: 5px;
            margin-right: 50px;
        }

        .main{
            margin-top: 100px;
            display: flex;
            justify-content:center;
            flex-wrap: wrap;
            margin-bottom:90px;
        }

        .product{
            border:2px solid blueviolet;
            margin: 10px;
            max-width: 300px;
            padding: 30px;
            text-align: center;
        }

        .product a{
            display: block;
            text-decoration: none;
            color: black;
            background-color: greenyellow;
            padding: 10px;
            margin-top: 10px;
            width: 100%;
        }

        .product img{
            width: 150px;
        }

        .productPrice{
            opacity: 70%;
        }

        .footer{
            display: flex;
            justify-content: center;
            align-items: center;
            background-color:gray;
            position: fixed;
            bottom: 0;
            padding: 20px;
            width:100%;
        }

        .footer p{
            text-align: center;
        }

        @media(max-width: 400px){
            .header{
                display: flex;
                flex-direction: column;
            }

            .footer{
                display: flex;
                flex-direction: row;
            }
        }
    </style>
</head>

<body>

<header class="header">

    <a href="index.php">Kartify</a>

    <ul>
        <?php while($row_category = mysqli_fetch_assoc($result_category)){ ?>
            <li>
                <!-- ✅ FIXED LINK -->
                <a href="index.php?category_name=<?php echo $row_category['name']; ?>">
                    <?php echo $row_category['name']; ?>
                </a>
            </li>
        <?php } ?>
    </ul>

    <nav>
        <ul>
            <?php if(!isset($_SESSION['user_id'])){ ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Signup</a></li>
            <?php } ?>
            <li><a href="">Dashboard</a></li>
        </ul>
    </nav>

</header>

<main class="main">

    <?php while($row_product_category = mysqli_fetch_assoc($result_product_category)){ ?>

        <div class="product">
            <img src="image/<?php echo $row_product_category['image']; ?>" alt="productimg">
            <h2><?php echo $row_product_category['name']; ?></h2>
            <p><?php echo $row_product_category['description']; ?></p>
            <p><?php echo $row_product_category['stock']; ?></p>
            <p class="productPrice"><?php echo $row_product_category['price']; ?></p>
            <a href="#">Buy Now</a>
        </div>

    <?php } ?>

</main>

<footer class="footer">
    <p>copyright @Tasrian</p>
</footer>

</body>
</html>
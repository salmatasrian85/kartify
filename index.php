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
            overflow-x: hidden ;
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
            margin-bottom:90px ;
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
                background-color:gray ;
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
        <a href="index.php">shop</a>
        <nav>
            <ul>
                <li><a href="">Login</a></li>
                <li><a href="">Signup</a></li>
                <li><a href="">Dashboard</a></li>
            </ul>
        </nav>
    </header>
    <main class="main">
        <?php for($i = 0; $i<15; $i++){
            ?>
        <div class="product">
            <img src="image/img1.jpg" alt="productimg">
            <h2>Starry Night</h2>
            <p>Product Description</p>
            <p>600 TAKA</p>
            <p class="productPrice">price</p>
            <a href="#">Buy Now</a>
        </div>
        <?php } ?>
    
    </main>
    <footer class="footer">
        <p>copyright @Tasrian</p>
    </footer>
</body>
</html>
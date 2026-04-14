

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .registerdiv{
            margin-top: 200px;
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            justify-content: center;
        }
        .shoplink{
            display: block;
            width: 100px;
            position: fixed;
            top: 18%;
            left: 45%;
            text-align: center;
            text-decoration: none;
            background-color:lightgreen ;
            padding: 10px;
        }
        .registerdiv input{
            display: block;
            padding: 15px;
            margin: 8px;
        }
        .registerdiv textarea{
            display: block;
            padding: 15px;
            margin: 8px;
            width: 163px;
        }
        .button{
            width: 215px;
            background-color: lightcoral;
            border: none;
        }
        .button:hover{
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <a class = "shoplink" href="index.php">Kartify</a>
    <div class="registerdiv">
        <form action="register.php" method="post">
            <input type="text" name="name" placeholder="Enter Your Name here!" required>
            <input type="email" name="email" placeholder="Enter your Name here!" required>
            <input type="password" name="password" placeholder="Enter Your Password here!" required>
            <input type="text" name="phone" placeholder="Enter Your Phone Number here!" required>
            <textarea name="address" id="" placeholder="Enter Your Address Number here"> </textarea>
            <input class ="button" type="submit" name="submit" value="sign up" required>
        </form>
    </div>
    
</body>
</html>
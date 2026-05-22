<?php
session_start(); 
include "db.php";

if(isset($_SESSION['user_id'])){ 
    if($_SESSION['user_role'] == "user"){

        $user_id = $_SESSION['user_id'];

        $sql = "SELECT single_order.*, payments.payment_method
                FROM single_order
                LEFT JOIN payments ON payments.order_id = single_order.id
                WHERE single_order.user_id = '$user_id'
                ORDER BY single_order.id DESC";
        $result = mysqli_query($conn,$sql);

        if(!$result){
            echo "Error!: {$conn->error}";
        }

    }else{
        header("Location: admin/dashboard.php");
        exit();
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
<title>Customer Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

/* LAYOUT */
.container{
    width:100%;
}

.back-link{
    display:inline-block;
    margin-top:10px;
    padding:10px 16px;
    background:#111;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
}

/* MAIN */
.main{
    width:100%;
    background:#f5f6fa;
    min-height:100vh;
}

/* HEADER */
.header{
    padding:20px 30px;
    background:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.header h2{
    font-size:20px;
}

.user{
    font-size:14px;
    color:#555;
}

/* CONTENT */
.content{
    padding:30px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

thead{
    background:#111;
    color:white;
}

th, td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tbody tr:hover{
    background:#f9f9f9;
}

</style>
</head>

<body>

<div class="container">
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h2>My Orders</h2>
                <a href="index.php" class="back-link">Back to Shopping</a>
            </div>
            <div class="user">
                Welcome, <?php echo $_SESSION['user_name']; ?>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="card">

                <h3>Your Orders</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while($row=mysqli_fetch_assoc($result)){ ?>
                        <tr>
                            <td>#<?php echo $row['id'] ?></td>
                            <td>৳ <?php echo $row['total_amount']?></td>
                            <td><?php echo htmlspecialchars($row['payment_method'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($row['status'] ?? 'pending')); ?></td>
                        </tr>
                        <?php } ?> 
                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>
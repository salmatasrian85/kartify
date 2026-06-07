<?php
session_start(); 
include "db.php";

if(isset($_SESSION['user_id'])){ 
    if($_SESSION['user_role'] == "user"){

        $user_id = $_SESSION['user_id'];

        // Fetch all orders for this user
        $sql = "SELECT so.*, pay.payment_method
                FROM single_order so
                LEFT JOIN payments pay ON pay.order_id = so.id
                WHERE so.user_id = '$user_id'
                ORDER BY so.id DESC";
        
        $result = mysqli_query($conn, $sql);

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
<title>My Orders - Customer Dashboard</title>

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
    padding:10px 20px;
    background:#111;
    color:white;
    text-decoration:none;
    border-radius:6px;
    font-size:14px;
    font-weight:600;
    transition:0.3s;
}

.back-link:hover{
    background:#333;
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

.header-left{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.header-right{
    display:flex;
    align-items:center;
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
    text-align:left;
    border-bottom:1px solid #eee;
}

tbody tr:hover{
    background:#f9f9f9;
}

.action-link{
    color:#3498db;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
}

.action-link:hover{
    text-decoration:underline;
}

.empty-state{
    text-align:center;
    padding:40px 20px;
    color:#666;
}

.empty-state p{
    margin-top:10px;
    margin-bottom:20px;
}

.empty-state a{
    display:inline-block;
    background:#111;
    color:white;
    padding:10px 20px;
    border-radius:6px;
    text-decoration:none;
    font-weight:600;
}

.status-badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:4px;
    font-size:12px;
    font-weight:600;
    text-transform:uppercase;
}

.status-pending{
    background:#fff3cd;
    color:#856404;
}

.status-completed{
    background:#d4edda;
    color:#155724;
}

.status-cancelled{
    background:#f8d7da;
    color:#721c24;
}

</style>
</head>

<body>

<div class="container">
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                <h2>My Orders</h2>
            </div>
            <div class="header-right">
                <a href="index.php" class="back-link">Back to Shop</a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <div class="card">

                <h3>My Orders</h3>

                <?php if(mysqli_num_rows($result) == 0): ?>
                    <div class="empty-state">
                        <p>You haven't placed any orders yet.</p>
                        <a href="index.php">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)){ 
                                $status_class = 'status-' . strtolower($row['status']);
                            ?>
                            <tr>
                                <td>#<?php echo intval($row['id']); ?></td>
                                <td>Tk. <?php echo number_format($row['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_method'] ?? 'Cash on Delivery'); ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars(ucfirst($row['status'] ?? 'pending')); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php } ?> 
                        </tbody>
                    </table>
                <?php endif; ?>

            </div>

        </div>

    </div>

</div>


</body>
</html>

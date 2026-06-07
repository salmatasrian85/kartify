<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header('Location: ../login.php');
    exit();
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($order_id <= 0){
    echo 'Invalid order ID';
    exit();
}

// Fetch the order with product and payment info
$sql = "SELECT so.*, u.name AS user_name, u.email AS user_email, p.name AS product_title, p.price AS product_price, pay.payment_method, pay.total_amount AS payment_amount
        FROM single_order so
        LEFT JOIN users u ON so.user_id = u.id
        LEFT JOIN products p ON so.product_id = p.id
        LEFT JOIN payments pay ON pay.order_id = so.id
        WHERE so.id = '$order_id'";
$res = mysqli_query($conn, $sql);
if(!$res || mysqli_num_rows($res) == 0){
    echo 'Order not found';
    exit();
}
$order = mysqli_fetch_assoc($res);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details #<?php echo $order_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:Inter, Arial, sans-serif}
        body{background:#f5f6fa}
        .container{display:flex}
        .sidebar{width:240px;height:100vh;background:#111;color:white;padding:30px;position:fixed;overflow-y:auto}
        .logo{font-size:24px;margin-bottom:40px;letter-spacing:2px}
        .sidebar a{display:block;color:#bbb;text-decoration:none;margin:15px 0;font-size:14px}
        .sidebar a:hover{color:white}
        .main{margin-left:240px;width:100%;background:#f5f6fa;min-height:100vh}
        .header{padding:20px 30px;background:white;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 10px rgba(0,0,0,0.05)}
        .content{padding:30px}
        .box{background:#fff;padding:25px;border-radius:10px;max-width:900px;box-shadow:0 8px 20px rgba(0,0,0,0.06)}
        h1{font-size:20px;margin-bottom:8px;font-weight:600}
        h2{font-size:16px;margin-top:20px;margin-bottom:12px;font-weight:600}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th{background:#111;color:white;padding:12px;text-align:left;font-weight:600}
        td{padding:12px;border-bottom:1px solid #eee;text-align:left}
        tbody tr:hover{background:#f9f9f9}
        .section{margin-top:20px}
        .label{color:#666;font-weight:600;width:160px;display:inline-block}
        .status-badge{
            display:inline-block;
            padding:6px 12px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">KARTIFY</div>
            <a href="dashboard.php">Dashboard</a>
            <a href="addproduct.php">Add Product</a>
            <a href="displayproduct.php">Manage Products</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="vieworder.php">Customer Orders</a>
            <a href="admin_profile.php">Profile</a>
            <a href="../logout.php">Logout</a>
        </div>
        <div class="main">
            <div class="header">
                <h2>Order Details</h2>
                <div>Admin Panel</div>
            </div>
            <div class="content">
                <div class="box">
                    <h1>Order #<?php echo $order_id; ?></h1>

                    <div class="section">
                        <div><span class="label">User ID:</span> <?php echo intval($order['user_id']); ?></div>
                        <div><span class="label">Name:</span> <?php echo htmlspecialchars($order['user_name']); ?></div>
                        <div><span class="label">Email:</span> <?php echo htmlspecialchars($order['user_email']); ?></div>
                        <div><span class="label">Order Created:</span> <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></div>
                        <div><span class="label">Status:</span> <span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span></div>
                    </div>

                    <div class="section">
                        <h2>Product Details</h2>
                        <table>
                            <thead>
                                <tr><th>Product ID</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo intval($order['product_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_title'] ?? 'Unknown'); ?></td>
                                    <td>Tk <?php echo number_format($order['product_price'] ?? 0, 2); ?></td>
                                    <td><?php echo intval($order['quantity'] ?? 1); ?></td>
                                    <td>Tk <?php echo number_format($order['total_amount'], 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section">
                        <h2>Payment / Checkout Information</h2>
                        <div><span class="label">Payment Method:</span> <?php echo htmlspecialchars($order['payment_method'] ?? 'Cash on Delivery'); ?></div>
                        <div><span class="label">Total Amount:</span> Tk <?php echo number_format($order['payment_amount'] ?? $order['total_amount'], 2); ?></div>
                        <div><span class="label">Customer Phone:</span> <?php echo htmlspecialchars($order['customer_phone'] ?? '—'); ?></div>
                        <div><span class="label">Shipping Address:</span> <?php echo nl2br(htmlspecialchars($order['shipping_address'] ?? '—')); ?></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>

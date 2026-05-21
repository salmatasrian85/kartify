<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: vieworder.php");
    exit();
}

$order_id = intval($_GET['id']);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $sql = "UPDATE single_order SET status = '$status' WHERE id = '$order_id'";
    if(mysqli_query($conn, $sql)){
        header("Location: vieworder.php?msg=updated");
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM single_order WHERE id = '$order_id'");
$order = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Order Status</title>
  <style>
    body{ font-family: Arial, sans-serif; padding:20px; }
    .box{ max-width:600px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:8px; }
    label{ display:block;margin-bottom:8px;font-weight:600; }
    select, button{ padding:10px 14px;margin-top:8px; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Update Status for Order #<?php echo $order_id; ?></h2>
    <?php if(isset($error)){ echo '<div style="color:red;">'.htmlspecialchars($error).'</div>'; } ?>
    <form method="post">
      <label for="status">Status</label>
      <select name="status" id="status">
        <option value="pending" <?php if(($order['status'] ?? '')=='pending') echo 'selected'; ?>>Pending</option>
        <option value="delivered" <?php if(($order['status'] ?? '')=='delivered') echo 'selected'; ?>>Delivered</option>
        <option value="cancelled" <?php if(($order['status'] ?? '')=='cancelled') echo 'selected'; ?>>Cancelled</option>
      </select>
      <div style="margin-top:16px;">
        <button type="submit">Update</button>
        <a href="vieworder.php" style="margin-left:12px;">Back</a>
      </div>
    </form>
  </div>
</body>
</html>

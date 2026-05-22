<?php
session_start();
include "db.php";

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
if($product_id <= 0){
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM products WHERE id = '$product_id' LIMIT 1";
$res = mysqli_query($conn, $sql);
if(!$res || mysqli_num_rows($res) === 0){
    header("Location: index.php");
    exit();
}

$p = mysqli_fetch_assoc($res);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($p['name']); ?> - Product Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0;font-family:"Inter",sans-serif}
    body{background:#f8f8f8;color:#111}
    .container{max-width:1000px;margin:40px auto;padding:20px}
    .card{display:flex;gap:30px;background:#fff;padding:24px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.06)}
    .thumb{flex:1}
    .thumb img{width:100%;height:520px;object-fit:cover;border-radius:8px}
    .meta{flex:1}
    .meta h1{font-size:28px;margin-bottom:8px}
    .meta p{color:#555;margin-bottom:12px}
    .price{font-size:22px;font-weight:700;margin-bottom:12px}
    .btn{display:inline-block;padding:12px 18px;border-radius:10px;background:#111;color:#fff;text-decoration:none;margin-right:10px}
    .btn.secondary{background:transparent;color:#111;border:1px solid #111}
    .back{display:inline-block;margin-top:18px;color:#666;text-decoration:none}
  </style>
</head>
<body>
  <div class="container">
    <a class="back" href="index.php">← Back to Shop</a>
    <div class="card" style="margin-top:14px;">
      <div class="thumb">
        <img src="image/<?php echo htmlspecialchars($p['image']); ?>" alt="">
      </div>
      <div class="meta">
        <h1><?php echo htmlspecialchars($p['name']); ?></h1>
        <p style="font-weight:600;">Category: <?php echo htmlspecialchars($p['category_name']); ?></p>
        <div class="price">৳ <?php echo number_format($p['price'], 2); ?></div>
        <p><?php echo nl2br(htmlspecialchars($p['description'] ?? 'No description available.')); ?></p>
        <p style="margin-top:12px;color:<?php echo (isset($p['stock']) && $p['stock']>0)?'#1b6c3f':'#b21b2d'; ?>;font-weight:600;"><?php echo isset($p['stock'])?($p['stock']>0? 'In Stock':'Out of Stock') : 'Stock N/A'; ?></p>

        <div style="margin-top:18px;">
          <?php if(isset($p['stock']) && $p['stock'] > 0): ?>
            <a href="add_to_cart.php?product_id=<?php echo $p['id']; ?>" class="btn">Add to Cart</a>
          <?php else: ?>
            <span class="btn secondary" style="opacity:0.6;cursor:not-allowed;">Out of Stock</span>
          <?php endif; ?>
          <a href="cart.php" class="btn secondary">View Cart</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

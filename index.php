<?php  
session_start();
include "db.php";

/* CATEGORY LIST */
$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($conn, $sql_category);

/* ALL PRODUCTS */
$sql_product_category = "SELECT * FROM products";

/* CATEGORY FILTER */
if(isset($_GET['category_name']) && $_GET['category_name'] != ""){
    $category_name = mysqli_real_escape_string($conn, $_GET['category_name']);
    $sql_product_category = "SELECT * FROM products WHERE category_name = '$category_name'";
}

$result_product_category = mysqli_query($conn, $sql_product_category);
?>

<!DOCTYPE html>
<html>
<head>
<title>KARTIFY</title>

<style>
body { margin:0; font-family:Arial; background:#fbfaf8; }

/* NAVBAR */
.navbar {
  display:flex;
  justify-content:space-between;
  padding:20px 60px;
  align-items:center;
  background:#fbfaf8;
}

.logo {
  font-size:24px;
  font-weight:bold;
}

nav a {
  margin-left:15px;
  text-decoration:none;
  color:black;
}

nav a:hover {
  color:#c5a059;
}

/* GALLERY */
.gallery {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(250px,280px));
  gap:30px;
  justify-content:center;
  padding:40px;
}

.card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  width: 100%;
  max-width: 280px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

/* REMOVE overlay completely */
.overlay {
  position: static;
  background: none;
  color: black;
  padding: 15px;
}

.overlay h3 {
  margin: 0 0 5px;
  font-size: 16px;
}

.overlay p {
  margin: 0;
  font-weight: bold;
  color: #c5a059;
}

/* STYLES FOR STOCK ELEMENT */
.stock-status {
  font-size: 13px;
  color: #666;
  margin-top: 5px;
}

.out-of-stock {
  color: #d9534f;
  font-weight: bold;
}

.buy-btn {
  display: inline-block;
  margin-top: 10px;
  padding: 6px 12px;
  background: #c5a059;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
}

/* DISABLED BUTTON FOR OUT OF STOCK */
.buy-btn.disabled {
  background: #ccc;
  color: #666;
  cursor: not-allowed;
  pointer-events: none; /* disables click functionality */
}
</style>
</head>

<body>

<header class="navbar">

  <div class="logo">KARTIFY</div>

  <nav>
    <a href="index.php">All</a>

    <?php while($row_category = mysqli_fetch_assoc($result_category)){ ?>
      <a href="index.php?category_name=<?php echo $row_category['name']; ?>">
        <?php echo $row_category['name']; ?>
      </a>
    <?php } ?>
  </nav>

  <nav>

    <!-- If user is NOT logged in -->
    <?php if(!isset($_SESSION['user_id'])){ ?>
        <a href="login.php">Login</a>
        <a href="register.php">Signup</a>
    <?php } ?>

    <!-- Show "My Orders" ONLY if logged in AND role is user -->
    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == "user"){ ?>
        <a href="myorders.php">My Orders</a>
    <?php } ?>

</nav>

</header>

<h2 style="text-align:center;">Products</h2>

<section class="gallery">

<?php while($row = mysqli_fetch_assoc($result_product_category)){ ?>

  <div class="card">
    
    <img src="image/<?php echo $row['image']; ?>" alt="">

    <div class="overlay">
      <h3><?php echo $row['name']; ?></h3>
      <p><?php echo $row['price']; ?> ৳</p>

      <div class="stock-status">
        <?php if($row['stock'] > 0){ ?>
            Stock: <?php echo $row['stock']; ?> items available
        <?php } else { ?>
            <span class="out-of-stock">Out of Stock</span>
        <?php } ?>
      </div>

      <?php if($row['stock'] > 0){ ?>
          <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="singleorder.php?product_id=<?php echo $row['id']; ?>" class="buy-btn">
              Buy Now
            </a>
          <?php } else { ?>
            <a href="login.php" class="buy-btn">
              Buy Now
            </a>
          <?php } ?>
      <?php } else { ?>
          <a href="#" class="buy-btn disabled">Unavailable</a>
      <?php } ?>

    </div>

  </div>

<?php } ?>

</section>

</body>
</html>
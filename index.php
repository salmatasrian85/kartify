<?php 
session_start();
include "db.php";

/* ALL PRODUCTS */
$sql_product_category = "SELECT * FROM products";

/* CATEGORY FILTER */
if(isset($_GET['category_name']) && $_GET['category_name'] != ""){
    $category_name = mysqli_real_escape_string($conn, $_GET['category_name']);
    $sql_product_category = "SELECT * FROM products WHERE category_name = '$category_name'";
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
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Art Gallery</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

<style>

/* ===== BASE ===== */
body {
  margin: 0;
  font-family: "Inter", sans-serif;
  background: #fbfaf8;
  color: #1a1a1a;
}

:root {
  --cream: #fbfaf8;
  --ink: #1a1a1a;
  --gold: #c5a059;
}

/* ===== NAVBAR ===== */
.navbar {
  display: flex;
  justify-content: space-between;
  padding: 20px 60px;
  align-items: center;
}

.logo {
  font-family: "Playfair Display", serif;
  font-size: 24px;
  font-weight: 700;
}

nav a {
  margin-left: 20px;
  text-decoration: none;
  color: var(--ink);
}

nav a:hover {
  color: var(--gold);
}

/* ===== HERO ===== */
.hero {
  text-align: center;
  padding: 80px 20px;
}

.hero h1 {
  font-family: "Playfair Display", serif;
  font-size: 42px;
  font-style: italic;
  margin-bottom: 10px;
}

.hero p {
  max-width: 600px;
  margin: auto;
  color: #555;
  font-size: 16px;
}
/* ===== GALLERY ===== */
.gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 280px));
  justify-content: center;   /* 🔥 CENTER ITEMS */
  gap: 30px;
  padding: 40px 60px;
}

/* ===== CARD ===== */
.card {
  position: relative;
  height: 280px;   /* 🔽 slightly smaller */
  width: 100%;
  max-width: 280px; /* 🔥 prevents oversize */
  border-radius: 12px;
  overflow: hidden;
}

/* 🔥 REAL IMAGE */
.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: 0.4s;
}

/* overlay */
.overlay {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 20px;
  background: linear-gradient(to top, rgba(26,26,26,0.8), transparent);
  color: white;
}

.overlay h3 {
  margin: 0;
  font-family: "Playfair Display", serif;
}

/* hover */
.card:hover img {
  transform: scale(1.1);
}

</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<header class="navbar">
  <div class="logo">kARTify</div>

  <!-- CATEGORY -->
  <nav>
    <a href="index.php">All</a>
    <?php while($row_category = mysqli_fetch_assoc($result_category)){ ?>
      <a href="index.php?category_name=<?php echo $row_category['name']; ?>">
        <?php echo $row_category['name']; ?>
      </a>
    <?php } ?>
  </nav>

  <!-- AUTH -->
  <nav>
    <?php if(!isset($_SESSION['user_id'])){ ?>
      <a href="login.php">Login</a>
      <a href="register.php">Signup</a>
    <?php } ?>
    <a href="#">Dashboard</a>
  </nav>
</header>

<!-- ===== HERO ===== -->
<section class="hero">

  <h1>Where Vision Meets Reality.</h1>

  <p>
    Explore our curated collection of contemporary masterworks, 
    hand-selected to elevate your environment and inspire your soul.
  </p>

</section>
<!-- ===== PRODUCTS (GALLERY STYLE) ===== -->
<section class="gallery">

<?php while($row = mysqli_fetch_assoc($result_product_category)){ ?>

  <div class="card">
    
    <img src="image/<?php echo $row['image']; ?>" alt="">

    <div class="overlay">
      <h3><?php echo $row['name']; ?></h3>
      <p><?php echo $row['price']; ?> ৳</p>
    </div>

  </div>

<?php } ?>

</section>

</body>
</html>
<?php
session_start();
include "db.php";

/* CART COUNT */
$cart_count = 0;
if(isset($_SESSION['cart'])){
    $cart_count = array_sum($_SESSION['cart']);
}

/* FETCH CATEGORIES */
$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($conn, $sql_category);

/* DEFAULT PRODUCTS */
$search = '';
$sql = "SELECT * FROM products";
$where = [];

/* CATEGORY FILTER */
if(isset($_GET['category_name']) && $_GET['category_name'] != ""){
    $category_name = mysqli_real_escape_string($conn, $_GET['category_name']);
    $where[] = "category_name = '$category_name'";
}

/* SEARCH FILTER */
if(isset($_GET['search']) && trim($_GET['search']) != ""){
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $where[] = "(name LIKE '%$search%' OR description LIKE '%$search%' OR category_name LIKE '%$search%')";
}

if(count($where) > 0){
    $sql = "SELECT * FROM products WHERE " . implode(' AND ', $where);
}

$result = mysqli_query($conn, $sql);

/* FEATURED PRODUCTS */
$sql_featured = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
$result_featured = mysqli_query($conn, $sql_featured);
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <title>KARTIFY</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>

    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body{
      font-family: "Inter", sans-serif;
      background: #fdfcfb;
      color: #1a1a1a;
    }

    img{
      display: block;
      width: 100%;
    }

    .serif{
      font-family: "Cormorant Garamond", serif;
    }

    /* =========================
        NAVBAR
    ========================= */

    .nav{
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 22px 40px;
      position: sticky;
      top: 0;
      background: rgba(253,252,251,0.92);
      backdrop-filter: blur(10px);
      z-index: 1000;
      border-bottom: 1px solid #ece7df;
    }

    .logo{
      font-size: 34px;
      font-weight: 700;
      letter-spacing: 2px;
    }

    .nav-links{
      display: flex;
      gap: 28px;
    }

    .nav-links a{
      text-decoration: none;
      color: #555;
      font-size: 14px;
      transition: 0.3s;
    }

    .nav-links a:hover{
      color: black;
    }

    .nav-right{
      display: flex;
      align-items: center;
      gap: 18px;
    }

    .nav-search{
      flex: 1;
      display: flex;
      justify-content: center;
      padding: 0 20px;
    }

    .nav-search form{
      width: 100%;
      max-width: 420px;
      position: relative;
    }

    .nav-search input{
      width: 100%;
      padding: 10px 18px;
      border-radius: 30px;
      border: 1px solid #ddd;
      background: white;
      outline: none;
      font-size: 14px;
    }

    .nav-search button{
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      color: #111;
      cursor: pointer;
      font-size: 16px;
    }

    .nav-right input{
      padding: 10px 16px;
      border-radius: 30px;
      border: 1px solid #ddd;
      background: white;
      outline: none;
      width: 220px;
    }
    .auth-links{
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .cart-wrap{
      order: 2;
    }

    .auth-btn{
      padding: 8px 14px;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s;
      border: 1px solid #111;
    }

    .login-btn{
      color: #111;
      background: transparent;
    }

    .login-btn:hover{
      background: #111;
      color: white;
    }

    .signup-btn{
      background: #111;
      color: white;
    }

    .signup-btn:hover{
      background: #333;
    }

    .cart{
      width: 42px;
      height: 42px;
      border-radius: 50%;
      border: none;
      cursor: pointer;
      background: #111;
      color: white;
      font-size: 16px;
    }

    .cart-wrap{ position: relative; display: inline-block; }
    .cart-badge{
      position: absolute;
      top: -8px;
      right: -8px;
      background: #e74c3c;
      color: white;
      font-size: 12px;
      padding: 4px 7px;
      border-radius: 20px;
      line-height: 1;
      min-width: 22px;
      text-align: center;
    }

    /* Profile icon */
    .profile-icon{
      width:34px;
      height:34px;
      border-radius:50%;
      background:#111;
      color:white;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      font-size:13px;
      font-weight:600;
      text-decoration:none;
      margin-left:8px;
    }

    /* =========================
        HERO
    ========================= */

    .hero{
      height: 85vh;
      background:
      linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
      url("https://images.unsplash.com/photo-1513364776144-60967b0f800f")
      center/cover;
      display: flex;
      align-items: center;
      color: white;
    }

    .hero-content{
      padding: 0 60px;
      max-width: 700px;
    }

    .badge{
      display: inline-block;
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25);
      padding: 8px 16px;
      border-radius: 30px;
      font-size: 11px;
      letter-spacing: 2px;
      margin-bottom: 25px;
    }

    .hero h2{
      font-size: 76px;
      line-height: 1;
      margin-bottom: 25px;
    }

    .hero p{
      font-size: 17px;
      line-height: 1.8;
      color: rgba(255,255,255,0.85);
      max-width: 520px;
    }

    .btn{
      margin-top: 30px;
      padding: 15px 28px;
      border-radius: 40px;
      border: none;
      cursor: pointer;
      background: #d8d1c7;
      color: black;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn:hover{
      background: white;
    }
    .featured{
      padding: 60px 40px;
      text-align: center;
    }

    .featured h2{
      font-size: 52px;
      margin-bottom: 40px;
    }

    .featured-grid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 300px));
      justify-content: center;
      gap: 30px;
    }

    /* =========================
        FILTERS
    ========================= */

    .filters{
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      align-items: center;
      padding: 25px 40px;
      border-bottom: 1px solid #ece7df;
    }

    .filters .filter-label{
      font-size: 24px;
      font-weight: 500;
      margin-right: 16px;
      color: #222;
    }

    .filters button{
      padding: 10px 18px;
      border-radius: 4px;
      border: 1px solid #111;
      background: transparent;
      cursor: pointer;
      transition: 0.3s;
      font-size: 13px;
      font-weight: 500;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }
    .cart-wrap{
  position: relative;
  display: inline-block;
}

.cart-badge{
  position: absolute;
  top: -8px;
  right: -8px;
  background: #e74c3c;
  color: white;
  font-size: 12px;
  padding: 4px 7px;
  border-radius: 20px;
  min-width: 22px;
  text-align: center;
}


.cart:hover{
  transform: scale(1.1);
}

.cart-badge{
  animation: pop 0.3s ease;
}

@keyframes pop{
  0%{ transform: scale(0); }
  100%{ transform: scale(1); }
}

    .filters button:hover{
      background: #111;
      color: white;
    }

    .filters .active{
      background: #111;
      color: white;
    }

    /* =========================
        GALLERY GRID
    ========================= */

    .grid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(290px, 300px));
      justify-content: center;
      gap: 35px;
      padding: 50px 40px;
    }

    .card{
      width: 100%;
      max-width: 300px; /* 🔥 limit size */
      margin: auto;
    }

    .card:hover{
      transform: translateY(-8px);
    }

    .card img{
      height: 360px;
      object-fit: cover;
    }

    .card-content{
      padding: 24px;
    }

    .card h3{
      font-size: 34px;
      margin-bottom: 8px;
    }

    .card p{
      color: #666;
      margin-bottom: 14px;
    }

    .price{
      font-size: 20px;
      font-weight: 700;
    }

    .buy-btn{
      display: inline-block;
      margin-top: 14px;
      padding: 12px 20px;
      border-radius: 30px;
      background: #111;
      color: white;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: 0.3s;
    }

    .details-btn{
      display: inline-block;
      margin-top: 14px;
      padding: 12px 20px;
      border-radius: 30px;
      background: transparent;
      color: #111;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      border: 1px solid #111;
      transition: 0.3s;
    }

    .buy-btn:hover{
      background: #333;
    }

    /* =========================
        NEWSLETTER
    ========================= */

    .newsletter{
      background: #000;
      color: white;
      padding: 70px 60px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
      gap: 40px;
    }

    .newsletter-left h2{
      font-size: 68px;
      margin-bottom: 18px;
      line-height: 1;
    }

    .newsletter-left p{
      color: rgba(255,255,255,0.75);
      max-width: 560px;
      line-height: 1.8;
      font-size: 15px;
    }

    .newsletter-right{
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 28px;
    }

    .newsletter-form{
      display: flex;
      gap: 0;
      flex-wrap: wrap;
    }

    .newsletter-form input{
      width: 250px;
      padding: 18px 20px;
      background: transparent;
      border: 1px solid rgba(255,255,255,0.4);
      color: white;
      outline: none;
      font-size: 14px;
    }

    .newsletter-form input::placeholder{
      color: rgba(255,255,255,0.7);
    }

    .newsletter-form button{
      width: 220px;
      border: none;
      background: #e7e3dc;
      color: #111;
      font-weight: 700;
      letter-spacing: 1px;
      cursor: pointer;
      transition: 0.3s;
    }
    .newsletter-form button:hover{
      background: white;
    }

    .social-icons{
      display: flex;
      gap: 16px;
    }

    .social-icons a{
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: white;
      color: black;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 18px;
      transition: 0.3s;
    }

    .social-icons a:hover{
      transform: translateY(-4px);
      background: #d8d1c7;
    }

    /* =========================
        FOOTER
    ========================= */

    .footer{
      background: #efefef;
      padding: 90px 60px 40px;
    }

    .footer-container{
      max-width: 1300px;
      margin: auto;
    }

    .footer-top{
      display: grid;
      grid-template-columns: 1.3fr 1fr 1fr 1fr;
      gap: 70px;
    }

    .footer-logo{
      font-size: 54px;
      margin-bottom: 28px;
      color: #c87d17;
    }

    .footer-desc{
      color: #555;
      line-height: 1.8;
      max-width: 300px;
    }

    .footer h3{
      font-size: 32px;
      margin-bottom: 25px;
      letter-spacing: 1px;
    }

    .footer p{
      margin-bottom: 12px;
      color: #444;
      line-height: 1.8;
    }

    .footer ul{
      list-style: none;
    }

    .footer ul li{
      margin-bottom: 14px;
    }

    .footer ul li a{
      text-decoration: none;
      color: #444;
      transition: 0.3s;
    }

    .footer ul li a:hover{
      color: black;
      padding-left: 5px;
    }

    .contact-link{
      margin-top: 20px;
      display: inline-block;
      color: black;
      font-weight: 600;
    }

    .footer-bottom{
      border-top: 1px solid #d4d4d4;
      margin-top: 70px;
      padding-top: 25px;
      text-align: center;
      color: #666;
      font-size: 13px;
      letter-spacing: 1px;
    }

    /* =========================
        RESPONSIVE
    ========================= */

    @media(max-width:992px){

      .footer-top{
        grid-template-columns: repeat(2,1fr);
      }

      .newsletter-left h2{
        font-size: 48px;
      }

      .hero h2{
        font-size: 56px;
      }

  

    }

    @media(max-width:768px){

      .nav{
        flex-direction: column;
        gap: 20px;
      }

      .nav-links{
        flex-wrap: wrap;
        justify-content: center;
      }

      .hero{
        height: 70vh;
      }

      .hero-content{
        padding: 0 25px;
      }

      .hero h2{
        font-size: 42px;
      }

      .browse-top{
        flex-direction: column;
        align-items: stretch;
      }

      .browse-title h2{
        font-size: 42px;
      }

      .newsletter{
        padding: 60px 25px;
      }

      .newsletter-form{
        flex-direction: column;
        width: 100%;
      }

      .newsletter-form input,
      .newsletter-form button{
        width: 100%;
      }

      .newsletter-right{
        width: 100%;
        align-items: flex-start;
      }

      .footer{
        padding: 50px 25px 35px;
      }

      .footer-top{
        grid-template-columns: 1fr;
        gap: 50px;
      }

      .footer-logo{
        font-size: 42px;
      }

      .grid{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(290px, 300px));
        justify-content: center; /* 🔥 important */
        gap: 35px;
        padding: 50px 40px;
      }

      .filters{
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
      }

      .filters .filter-label{
        margin-right: 0;
        margin-bottom: 5px;
      }

      .browse-section{
        padding: 35px 20px 15px;
      }

    }

  </style>

</head>

<body>

  <nav class="nav">

  <h1 class="logo serif">KARTIFY</h1>

  <div class="nav-search">
    <form action="index.php" method="get">
      <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>

  <div class="nav-right">

    <?php if(isset($_SESSION['user_id'])){ ?>

      <div class="auth-links">

        <a href="profile.php" class="profile-icon" title="Profile">
          <?php echo strtoupper(substr($_SESSION['user_name'],0,1)); ?>
        </a>

        <?php if(($_SESSION['user_role'] ?? '') === 'user'): ?>
          <a href="myorders.php" class="auth-btn login-btn">My Orders</a>
        <?php endif; ?>

        <a href="logout.php" class="auth-btn signup-btn">Logout</a>

      </div>

    <?php } else { ?>

      <div class="auth-links">
        <a href="login.php" class="auth-btn login-btn">Login</a>
        <a href="register.php" class="auth-btn signup-btn">Signup</a>
      </div>

    <?php } ?>

    <?php if(isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'user'): ?>
      <div class="cart-wrap" style="margin-left:10px;">
        <a href="cart.php">
          <button class="cart">
            <i class="fa fa-shopping-bag"></i>
          </button>
        </a>

        <?php if($cart_count > 0){ ?>
          <span class="cart-badge">
            <?php echo $cart_count; ?>
          </span>
        <?php } ?>
      </div>
    <?php endif; ?>

  </div>

</nav>

  <header class="hero">

    <div class="hero-content">

      <h2 class="serif">
        Art that speaks <br>
        to the soul.
      </h2>

      <p>
        Welcome to our art shop—a space where creativity comes to life. From vibrant paintings to handcrafted pieces, every item here tells a story. Whether you're an art lover, a collector, or simply exploring, we invite you to discover inspiration in every corner.
      </p>

    </div>
    
  </header>
  <section class="featured">

  <h2 class="serif">Featured Works</h2>

  <div class="featured-grid">

    <?php while($f = mysqli_fetch_assoc($result_featured)){ ?>

      <div class="card">

        <img src="image/<?php echo $f['image']; ?>" alt="">

        <div class="card-content">

          <h3 class="serif">
            <?php echo htmlspecialchars($f['name']); ?>
          </h3>

          <p>
            <?php echo htmlspecialchars($f['category_name']); ?>
          </p>

          <span class="price">
            Tk. <?php echo $f['price']; ?>
          </span>

          <?php if(isset($f['stock']) && $f['stock'] <= 0){ ?>
            <span class="buy-btn" style="background:#888;cursor:not-allowed;">Out of Stock</span>
          <?php } else { ?>
            <div style="display:flex; gap:10px; align-items:center; margin-top:10px;">
              <a href="add_to_cart.php?product_id=<?php echo $f['id']; ?>" class="buy-btn">Add to Cart</a>
              <a href="productdetails.php?product_id=<?php echo $f['id']; ?>" class="details-btn">Product Details</a>
            </div>
          <?php } ?>

        </div>

      </div>

    <?php } ?>

  </div>

</section>

  <section class="filters">

  <span class="filter-label">Shop by Category</span>

  <a href="?#filters">
  <button class="<?php if(!isset($_GET['category_name'])) echo 'active'; ?>">
    All
  </button>
</a>

  <?php while($row_category = mysqli_fetch_assoc($result_category)){ ?>

    <a href="index.php?category_name=<?php echo urlencode($row_category['name']); ?>">
      
      <button class="<?php 
        if(isset($_GET['category_name']) && $_GET['category_name'] == $row_category['name']) 
          echo 'active'; 
      ?>">
        <?php echo htmlspecialchars($row_category['name']); ?>
      </button>

    </a>

  <?php } ?>

</section>

  <main class="grid">

    <?php while($row = mysqli_fetch_assoc($result)){ ?>

      <div class="card">

        <img
          src="image/<?php echo $row['image']; ?>"
          alt=""
        >

        <div class="card-content">

          <h3 class="serif">
            <?php echo htmlspecialchars($row['name']); ?>
          </h3>

          <p>
            <?php echo htmlspecialchars($row['category_name']); ?>
          </p>

          <span class="price">
            Tk. <?php echo $row['price']; ?>
          </span>

          <?php if(isset($row['stock']) && $row['stock'] <= 0){ ?>
            <span class="buy-btn" style="background:#888;cursor:not-allowed;">Out of Stock</span>
          <?php } else { ?>
            <div style="display:flex; gap:10px; align-items:center; margin-top:10px;">
              <a href="add_to_cart.php?product_id=<?php echo $row['id']; ?>" class="buy-btn">Add to Cart</a>
              <a href="productdetails.php?product_id=<?php echo $row['id']; ?>" class="details-btn">Product Details</a>
            </div>
          <?php } ?>

        </div>

      </div>

    <?php } ?>

  </main>

  <section class="newsletter">

    <div class="newsletter-left">

      <h2 class="serif">
        Join our community
      </h2>

      <p>
        Receive curated updates on new collections,
        featured artists and exclusive artwork releases
        from KARTIFY Contemporary Art Gallery.
      </p>

    </div>

    <div class="newsletter-right">

      <div class="newsletter-form">

        <input type="text" placeholder="Full Name*">

        <input type="email" placeholder="Email Address*">

        <button>SUBSCRIBE</button>

      </div>

      <div class="social-icons">

        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>

      </div>

    </div>

  </section>

  <footer class="footer">

    <div class="footer-container">

      <div class="footer-top">

        <div>

          <h2 class="footer-logo serif">
            KARTIFY
          </h2>

          <p class="footer-desc">
            Contemporary art marketplace connecting
            collectors with emerging and established artists worldwide.
          </p>

        </div>

        <div>

          <h3 class="serif">GET IN TOUCH</h3>

          <p>P: +880 1234-567890</p>

          <p>E: info@kartify.com</p>

          <p>
            House 12, Road 7 <br>
            Dhanmondi, Dhaka
          </p>

          <a href="#" class="contact-link">
            Contact us / Locations
          </a>

        </div>

        <div>

          <h3 class="serif">DISCOVER</h3>

          <ul>
            <li><a href="#">Art & Artists</a></li>
            <li><a href="#">Exhibitions</a></li>
            <li><a href="#">Paintings</a></li>
            <li><a href="#">Sculptures</a></li>
            <li><a href="#">Photography</a></li>
          </ul>

        </div>
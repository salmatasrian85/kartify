<?php
include "db.php";

/* FETCH PRODUCTS */
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Artisan Gallery</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>

    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Inter", sans-serif;
      background: #fdfcfb;
      color: #1a1a1a;
    }

    /* fonts */
    .serif {
      font-family: "Cormorant Garamond", serif;
    }

    /* NAV */
    .nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      position: sticky;
      top: 0;
      background: rgba(253,252,251,0.9);
      backdrop-filter: blur(10px);
      z-index: 100;
    }

    .logo {
      font-size: 24px;
    }

    .nav-links a {
      margin: 0 10px;
      text-decoration: none;
      color: #555;
      transition: 0.3s;
    }

    .nav-links a:hover{
      color: black;
    }

    .nav-right input {
      padding: 8px 12px;
      border-radius: 20px;
      border: 1px solid #ddd;
      outline: none;
    }

    .cart{
      padding: 8px 12px;
      border: none;
      border-radius: 50%;
      cursor: pointer;
    }

    /* HERO */
    .hero {
      height: 80vh;
      background: url("https://images.unsplash.com/photo-1513364776144-60967b0f800f") center/cover;
      position: relative;
      display: flex;
      align-items: center;
      color: white;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.4);
    }

    .hero-content {
      position: relative;
      padding: 60px;
      max-width: 600px;
      z-index: 2;
    }

    .badge {
      background: rgba(255,255,255,0.2);
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 10px;
      letter-spacing: 2px;
    }

    .hero h2 {
      font-size: 60px;
      margin: 20px 0;
    }

    .btn {
      padding: 12px 24px;
      border-radius: 30px;
      border: none;
      cursor: pointer;
      margin-top: 20px;
      font-weight: bold;
    }

    /* FILTERS */
    .filters {
      display: flex;
      gap: 10px;
      padding: 20px 40px;
      flex-wrap: wrap;
    }

    .filters button {
      padding: 8px 14px;
      border-radius: 20px;
      border: none;
      background: #eee;
      cursor: pointer;
    }

    .filters .active {
      background: #5A5A40;
      color: white;
    }

    /* GRID */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      padding: 40px;
    }

    /* CARD */
    .card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      transition: 0.4s;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .card:hover{
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 350px;
      object-fit: cover;
    }

    .card-content{
      padding: 20px;
    }

    .card h3 {
      margin-bottom: 8px;
      font-size: 28px;
    }

    .card p {
      color: gray;
      margin-bottom: 10px;
    }

    .price{
      font-weight: bold;
      font-size: 18px;
    }

    /* FOOTER */
    .footer {
      background: #1a1a1a;
      color: white;
      padding: 80px 40px 30px;
      margin-top: 60px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 60px;
      max-width: 1200px;
      margin: auto;
    }

    .footer-brand h2 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .footer-brand p {
      color: rgba(255,255,255,0.6);
      line-height: 1.6;
      max-width: 400px;
    }

    .footer h4 {
      font-size: 12px;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 20px;
      color: rgba(255,255,255,0.8);
    }

    .footer ul {
      list-style: none;
    }

    .footer ul li {
      margin-bottom: 12px;
      color: rgba(255,255,255,0.6);
      cursor: pointer;
    }

    .footer ul li:hover {
      color: white;
    }

    .footer-bottom {
      margin-top: 60px;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.1);
      text-align: center;
      font-size: 10px;
      letter-spacing: 2px;
      color: rgba(255,255,255,0.4);
      text-transform: uppercase;
    }

    /* RESPONSIVE */
    @media(max-width:768px){

      .nav{
        flex-direction: column;
        gap: 20px;
      }

      .hero h2{
        font-size: 40px;
      }

      .footer-grid{
        grid-template-columns: 1fr;
      }

    }

  </style>
</head>

<body>

  <!-- NAV -->
  <nav class="nav">

    <h1 class="logo serif">KARTIFY</h1>

    <div class="nav-links">
      <a href="#">Gallery</a>
      <a href="#">Artists</a>
      <a href="#">Exhibitions</a>
      <a href="#">Store</a>
    </div>

    <div class="nav-right">
      <input type="text" placeholder="Search artists or works"/>
      <button class="cart">🛒</button>
    </div>

  </nav>

  <!-- HERO -->
  <header class="hero">

    <div class="hero-overlay"></div>

    <div class="hero-content">

      <span class="badge">Limited Collection 2026</span>

      <h2 class="serif">
        Art that speaks <br/>
        <em>to the soul.</em>
      </h2>

      <p>
        Curating exceptional contemporary works from global artists.
      </p>

      <button class="btn">Browse Collection →</button>

    </div>

  </header>

  <section class="filters">

  <button class="active">All</button>
  <button>Flower Vase Acrylic Painting</button>
  <button>Oil Painting</button>
  <button>Custom Artwork</button>
  <button>Texture Painting</button>

</section>

  <!-- DYNAMIC GALLERY -->
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
            ৳ <?php echo $row['price']; ?>
          </span>

        </div>

      </div>

    <?php } ?>

  </main>

  <!-- FOOTER -->
  <footer class="footer">

    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">

        <h2 class="serif">ARTISAN GALLERIES</h2>

        <p>
          A destination for art lovers and collectors.
        </p>

      </div>

      <!-- Collection -->
      <div>

        <h4>Collection</h4>

        <ul>
          <li>Abstract Paintings</li>
          <li>Modern Photography</li>
          <li>Sculptural Works</li>
          <li>New Arrivals</li>
        </ul>

      </div>

      <!-- Service -->
      <div>

        <h4>Service</h4>

        <ul>
          <li>Art Advisory</li>
          <li>Shipping & Returns</li>
          <li>Collector Portal</li>
          <li>Terms of Sale</li>
        </ul>

      </div>

    </div>

    <div class="footer-bottom">
      <p>© 2026 kartify. All rights reserved.</p>
    </div>

  </footer>

</body>
</html>
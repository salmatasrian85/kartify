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

  <title>KARTIFY — Contemporary Art Gallery</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
      gap: 10px;
    }

    .nav-right input{
      padding: 10px 16px;
      border-radius: 30px;
      border: 1px solid #ddd;
      background: white;
      outline: none;
      width: 220px;
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

    /* =========================
       TOP BROWSE SECTION
    ========================= */

    .browse-section{
      padding: 45px 40px 20px;
      border-bottom: 1px solid #ece7df;
    }

    .browse-top{
      display: flex;
      gap: 12px;
      align-items: center;
      margin-bottom: 40px;
    }

    .browse-top input{
      flex: 1;
      border: none;
      border-bottom: 2px solid #111;
      background: transparent;
      padding: 14px 0;
      outline: none;
      font-size: 15px;
    }

    .browse-top button{
      background: #111;
      color: white;
      border: none;
      padding: 14px 24px;
      cursor: pointer;
      font-size: 11px;
      letter-spacing: 1px;
    }

    .browse-title h2{
      font-size: 76px;
      letter-spacing: 2px;
      margin-bottom: 30px;
    }

    /* =========================
       FILTERS
    ========================= */

    .filters{
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      padding: 25px 40px;
      border-bottom: 1px solid #ece7df;
    }

    .filters button{
      padding: 10px 18px;
      border-radius: 30px;
      border: 1px solid #111;
      background: transparent;
      cursor: pointer;
      transition: 0.3s;
      font-size: 14px;
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
      grid-template-columns: repeat(auto-fit,minmax(290px,1fr));
      gap: 35px;
      padding: 50px 40px;
    }

    .card{
      background: white;
      overflow: hidden;
      transition: 0.4s;
      box-shadow: 0 5px 18px rgba(0,0,0,0.06);
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

      .browse-title h2{
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
        padding: 40px 20px;
      }

      .filters{
        padding: 20px;
      }

      .browse-section{
        padding: 35px 20px 15px;
      }

    }

  </style>

</head>

<body>

  <!-- NAVBAR -->
  <nav class="nav">

    <h1 class="logo serif">KARTIFY</h1>

    <div class="nav-links">
      <a href="#">Gallery</a>
      <a href="#">Arts</a>
      <a href="#">Exhibitions</a>
      <a href="#">Store</a>
    </div>

    <div class="nav-right">
      <input type="text" placeholder="Search arts or works">
      <button class="cart">🛒</button>
    </div>

  </nav>

  <!-- HERO -->
  <header class="hero">

    <div class="hero-content">

      <span class="badge">LIMITED COLLECTION 2026</span>

      <h2 class="serif">
        Art that speaks <br>
        to the soul.
      </h2>

      <p>
        Curating exceptional contemporary works from global artists and bringing timeless creativity into modern spaces.
      </p>

      <button class="btn">
        Browse Collection →
      </button>

    </div>

  </header>

  <!-- TOP BROWSE -->
  <section class="browse-section">

    <div class="browse-top">

      <input
        type="text"
        placeholder="Search by keyword, artist name, artwork title or exhibition"
      >

      <button>SEARCH</button>

    </div>

    <div class="browse-title">
      <h2>PAINTINGS</h2>
    </div>

  </section>

  <!-- FILTERS -->
  <section class="filters">

    <button class="active">All</button>
    <button>Flower Vase Acrylic Painting</button>
    <button>Oil Painting</button>
    <button>Custom Artwork</button>
    <button>Texture Painting</button>

  </section>

  <!-- PRODUCT GRID -->
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

  <!-- NEWSLETTER -->
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

        <a href="#">f</a>
        <a href="#">i</a>
        <a href="#">t</a>
        <a href="#">▶</a>

      </div>

    </div>

  </section>

  <!-- FOOTER -->
  <footer class="footer">

    <div class="footer-container">

      <div class="footer-top">

        <!-- LOGO -->
        <div>

          <h2 class="footer-logo serif">
            KARTIFY
          </h2>

          <p class="footer-desc">
            Contemporary art marketplace connecting
            collectors with emerging and established artists worldwide.
          </p>

        </div>

        <!-- CONTACT -->
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

        <!-- DISCOVER -->
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

        <!-- ABOUT -->
        <div>

          <h3 class="serif">ABOUT</h3>

          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Testimonials</a></li>
            <li><a href="#">Shipping & Delivery</a></li>
            <li><a href="#">Commission Artwork</a></li>
          </ul>

        </div>

      </div>

      <div class="footer-bottom">
        © 2026 KARTIFY — Contemporary Art Gallery
      </div>

    </div>

  </footer>

</body>
</html>
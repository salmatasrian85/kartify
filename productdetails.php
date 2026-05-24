<?php
session_start();
include "db.php";

/* ================= PRODUCT ================= */

$product_id = isset($_GET['product_id'])
    ? intval($_GET['product_id'])
    : 0;

if($product_id <= 0){
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM products 
        WHERE id = '$product_id' 
        LIMIT 1";

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

<title>
<?php echo htmlspecialchars($p['name']); ?> - Product Details
</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Inter", sans-serif;
}

body{
    background:#f5f6fa;
    color:#111;
}

/* ================= CONTAINER ================= */

.container{
    max-width:1100px;
    margin:40px auto;
    padding:20px;

    display:flex;
    flex-direction:column;
}

/* ================= BACK BUTTON ================= */

.back{
    align-self:flex-end;
    text-decoration:none;
    color:#111;
    font-size:14px;
    margin-bottom:15px;
    transition:0.3s;
}

.back:hover{
    opacity:0.7;
}

/* ================= CARD ================= */

.card{
    display:flex;
    gap:35px;

    background:white;

    padding:30px;

    border-radius:14px;

    box-shadow:0 10px 30px rgba(0,0,0,0.06);
}

/* ================= IMAGE ================= */

.thumb{
    flex:1;
}

.thumb img{
    width:100%;
    height:520px;
    object-fit:cover;
    border-radius:10px;
}

/* ================= META ================= */

.meta{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.meta h1{
    font-size:32px;
    font-weight:600;
    margin-bottom:12px;
}

.category{
    font-size:14px;
    font-weight:600;
    color:#666;
    margin-bottom:18px;
    text-transform:uppercase;
    letter-spacing:1px;
}

.price{
    font-size:26px;
    font-weight:700;
    margin-bottom:18px;
}

.description{
    color:#555;
    line-height:1.8;
    margin-bottom:20px;
}

.stock{
    font-weight:600;
    margin-bottom:22px;
}

.in-stock{
    color:#1b6c3f;
}

.out-stock{
    color:#b21b2d;
}

/* ================= BUTTON ================= */

.btn{
    display:inline-block;

    padding:12px 18px;

    border-radius:8px;

    background:#111;
    color:white;

    text-decoration:none;

    font-size:14px;
    font-weight:500;

    transition:0.3s;
}

.btn:hover{
    opacity:0.85;
}

.btn.secondary{
    background:#e74c3c;
    border:none;
    cursor:not-allowed;
}

/* ================= RESPONSIVE ================= */

@media(max-width:900px){

    .card{
        flex-direction:column;
    }

    .thumb img{
        height:400px;
    }

}

@media(max-width:600px){

    .container{
        padding:15px;
    }

    .card{
        padding:20px;
    }

    .meta h1{
        font-size:26px;
    }

    .price{
        font-size:22px;
    }

    .thumb img{
        height:300px;
    }

}

</style>

</head>

<body>

<div class="container">

    <!-- BACK -->

    <a href="index.php" class="back">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <!-- PRODUCT CARD -->

    <div class="card">

        <!-- IMAGE -->

        <div class="thumb">

            <img 
                src="image/<?php echo htmlspecialchars($p['image']); ?>" 
                alt=""
            >

        </div>

        <!-- PRODUCT INFO -->

        <div class="meta">

            <h1>
                <?php echo htmlspecialchars($p['name']); ?>
            </h1>

            <div class="category">
                Category:
                <?php echo htmlspecialchars($p['category_name']); ?>
            </div>

            <div class="price">
                ৳ <?php echo number_format($p['price'], 2); ?>
            </div>

            <div class="description">

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $p['description']
                        ?? 'No description available.'
                    )
                );
                ?>

            </div>

            <!-- STOCK -->

            <div class="stock 
                <?php echo (isset($p['stock']) && $p['stock'] > 0)
                    ? 'in-stock'
                    : 'out-stock'; ?>">

                <?php
                echo isset($p['stock'])
                    ? ($p['stock'] > 0
                        ? 'In Stock'
                        : 'Out of Stock')
                    : 'Stock N/A';
                ?>

            </div>

            <!-- BUTTON -->

            <div>

                <?php if(isset($p['stock']) && $p['stock'] > 0): ?>

                    <a 
                        href="add_to_cart.php?product_id=<?php echo $p['id']; ?>"
                        class="btn"
                    >
                        Add to Cart
                    </a>

                <?php else: ?>

                    <span 
                        class="btn secondary"
                        style="opacity:0.6;"
                    >
                        Out of Stock
                    </span>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

</body>
</html>
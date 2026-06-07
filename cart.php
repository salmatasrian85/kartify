<?php
session_start();
include "db.php";

function ensure_single_order_customer_columns($conn) {
    $columns = [
        'customer_name' => 'VARCHAR(255) NULL',
        'customer_email' => 'VARCHAR(255) NULL',
        'customer_phone' => 'VARCHAR(50) NULL',
        'shipping_address' => 'TEXT NULL',
        'status' => "VARCHAR(50) NOT NULL DEFAULT 'pending'",
        'total_amount' => 'DECIMAL(10,2) NOT NULL DEFAULT 0',
        'quantity' => 'INT NOT NULL DEFAULT 1',
    ];

    foreach ($columns as $column => $definition) {
        $check = mysqli_query($conn, "SHOW COLUMNS FROM single_order LIKE '$column'");
        if (!$check || mysqli_num_rows($check) === 0) {
            mysqli_query($conn, "ALTER TABLE single_order ADD COLUMN $column $definition");
        }
    }
}

$cart = $_SESSION['cart'] ?? [];
$cart_items = [];
$total_amount = 0;
$errors = [];
$customer_name = '';
$customer_email = '';
$customer_phone = '';
$shipping_address = '';

/* REMOVE ITEM */
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($cart[$remove_id])) {
        unset($cart[$remove_id]);
        $_SESSION['cart'] = $cart;
    }
    header("Location: cart.php");
    exit();
}

/* FORM HANDLING */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CHECKOUT */
    if (isset($_POST['checkout'])) {

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $customer_name = trim($_POST['customer_name'] ?? '');
        $customer_email = trim($_POST['customer_email'] ?? '');
        $customer_phone = trim($_POST['customer_phone'] ?? '');
        $shipping_address = trim($_POST['shipping_address'] ?? '');

        // Capture submitted live quantities directly from checkout form submission
        if (isset($_POST['qty']) && is_array($_POST['qty'])) {
            foreach ($_POST['qty'] as $product_id => $qty) {
                $product_id = intval($product_id);
                $qty = max(1, intval($qty));
                $cart[$product_id] = $qty;
            }
            $_SESSION['cart'] = $cart;
        }

        if ($customer_name === '') { $errors[] = "Please enter your name."; }
        if ($customer_email === '') { 
            $errors[] = "Please enter your email address."; 
        } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        if ($customer_phone === '') { $errors[] = "Please enter your phone number."; }
        if ($shipping_address === '') { $errors[] = "Please enter your shipping address."; }
        if (empty($cart)) { $errors[] = "Your cart is empty."; }

        $payment_method = 'cashon';

        if (empty($errors)) {
            $product_ids = array_map('intval', array_keys($cart));
            $id_list = implode(',', $product_ids);
            $result = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($id_list)");

            $total_amount = 0; // Reset to recalculate from refreshed db data
            while ($row = mysqli_fetch_assoc($result)) {
                $pid = $row['id'];
                $qty = $cart[$pid];
                $row['quantity'] = $qty;
                $row['subtotal'] = $row['price'] * $qty;
                $cart_items[$pid] = $row;
                $total_amount += $row['subtotal'];
            }

            $user_id = $_SESSION['user_id'];
            $escaped_name = mysqli_real_escape_string($conn, $customer_name);
            $escaped_email = mysqli_real_escape_string($conn, $customer_email);
            $escaped_phone = mysqli_real_escape_string($conn, $customer_phone);
            $escaped_address = mysqli_real_escape_string($conn, $shipping_address);

            ensure_single_order_customer_columns($conn);

            /* STOCK CHECK */
            foreach ($cart_items as $item) {
                if ($item['quantity'] > $item['stock']) {
                    $errors[] = "Not enough stock for {$item['name']}";
                    break;
                }
            }

            /* INSERT ORDER */
            if (empty($errors)) {
                $item_error = false;
                $main_order_id = null;

                foreach ($cart_items as $item) {
                    $pid = $item['id'];
                    $qty = $item['quantity'];
                    $amount = $item['subtotal'];

                    $item_insert = mysqli_query($conn, "
                        INSERT INTO single_order 
                        (user_id, product_id, quantity, total_amount, customer_name, customer_email, customer_phone, shipping_address, status)
                        VALUES ('$user_id', '$pid', '$qty', '$amount', '$escaped_name', '$escaped_email', '$escaped_phone', '$escaped_address', 'pending')
                    ");
                    
                    if (!$item_insert) {
                        $errors[] = "Error adding item to order: " . mysqli_error($conn);
                        $item_error = true;
                        break;
                    }

                    if ($main_order_id === null) {
                        $main_order_id = mysqli_insert_id($conn);
                    }

                    mysqli_query($conn, "UPDATE products SET stock = stock - '$qty' WHERE id = '$pid'");
                }

                if (!$item_error && $main_order_id !== null) {
                    $payment_insert = mysqli_query($conn, "
                        INSERT INTO payments 
                        (order_id, user_id, total_amount, payment_method)
                        VALUES ('$main_order_id', '$user_id', '$total_amount', '$payment_method')
                    ");
                    
                    if ($payment_insert) {
                        unset($_SESSION['cart']);
                        $_SESSION['success_message'] = 'Order placed successfully!';
                        header("Location: index.php");
                        exit();
                    } else {
                        $errors[] = "Error processing payment: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

/* LOAD CART */
if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");

    while ($row = mysqli_fetch_assoc($res)) {
        $qty = $cart[$row['id']];
        $row['quantity'] = $qty;
        $row['subtotal'] = $row['price'] * $qty;
        $cart_items[$row['id']] = $row;
        $total_amount += $row['subtotal'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KARTIFY Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Inter", sans-serif; }
body { background:#f8f8f8; color:#1a1a1a; }
.container { max-width:1160px; margin:40px auto; padding:0 20px; }
.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.header h1 { font-size:32px; letter-spacing:2px; }
.header a { color:#111; text-decoration:none; font-weight:600; }
.cart-table { width:100%; border-collapse:collapse; background:white; box-shadow:0 10px 35px rgba(0,0,0,0.06); }
.cart-table th, .cart-table td { padding:18px 16px; text-align:left; border-bottom:1px solid #eee; }
.cart-table th { background:#fafafa; font-size:14px; text-transform:uppercase; letter-spacing:1px; }
.product-name { display:flex; align-items:center; gap:16px; }
.product-name img { width:100px; height:80px; object-fit:cover; border-radius:10px; }
.qty-input { width:70px; padding:8px 10px; border:1px solid #ddd; border-radius:6px; }
.action-link { color:#e74c3c; text-decoration:none; font-size:14px; }
.summary { margin-top:24px; display:grid; gap:20px; grid-template-columns:1fr 320px; }
.summary-card, .checkout-card { background:white; padding:24px; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,0.05); }
.summary-item { display:flex; justify-content:space-between; margin-bottom:14px; font-size:15px; }
.summary-item.total { font-weight:700; font-size:18px; border-top:1px solid #eee; padding-top:14px; margin-top:14px; }
.checkout-card h2 { margin-bottom:18px; font-size:22px; }
.input-field { width:100%; padding:12px 14px; border-radius:10px; border:1px solid #ddd; outline:none; margin-bottom:12px; }
.btn { width:100%; padding:14px 18px; border:none; border-radius:12px; background:#111; color:white; font-size:16px; cursor:pointer; transition:0.3s; }
.btn:hover { background:#333; }
.notice { margin-bottom:20px; padding:14px 16px; border-radius:12px; }
.notice.error { background:#fdecea; color:#b21b2d; }
.empty-state { background:white; padding:40px; border-radius:16px; text-align:center; box-shadow:0 10px 28px rgba(0,0,0,0.05); }
.empty-state a { margin-top:18px; display:inline-block; text-decoration:none; color:white; background:#111; padding:12px 24px; border-radius:10px; }
.back-to-shop{ display:inline-block; background:#111; color:#fff !important; padding:8px 12px; border-radius:8px; text-decoration:none; font-weight:600; }
.back-to-shop:hover{ opacity:.9 }
.btn{ color:#fff !important; }
.checkout-label { display:block; margin-top:12px; margin-bottom:6px; font-weight:600; font-size:14px; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1>Shopping Cart</h1>
        </div>
        <?php if (!empty($cart_items)): ?>
            <div>
                <a class="back-to-shop" href="index.php">← Back to Shop</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="notice error">
            <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <div class="empty-state">
            <h2>Your cart is empty</h2>
            <p style="color:#666; margin-top:10px;">Add products from the gallery to see them here.</p>
            <a href="index.php">Back to Shop</a>
        </div>
    <?php else: ?>
        <form method="post">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr class="cart-row" data-id="<?php echo intval($item['id']); ?>" data-price="<?php echo floatval($item['price']); ?>">
                            <td>
                                <div class="product-name">
                                    <img src="image/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                        <span class="product-category"><?php echo htmlspecialchars($item['category_name']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>Tk. <?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input class="qty-input" type="number" name="qty[<?php echo intval($item['id']); ?>]" value="<?php echo intval($item['quantity']); ?>" min="1" oninput="updateRowTotals(this)">
                            </td>
                            <td class="subtotal-cell">Tk. <span><?php echo number_format($item['subtotal'], 2); ?></span></td>
                            <td><a class="action-link" href="cart.php?remove=<?php echo intval($item['id']); ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-card">
                    <h3>Order Summary</h3>
                    <div id="summary-items-list">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="summary-item" id="summary-item-<?php echo intval($item['id']); ?>">
                                <span class="summary-item-name"><?php echo htmlspecialchars($item['name']); ?> x <strong class="summary-item-qty"><?php echo intval($item['quantity']); ?></strong></span>
                                <span class="summary-item-subtotal">Tk. <?php echo number_format($item['subtotal'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="summary-item total">
                        <span>Total</span>
                        <span id="grand-total-display">Tk. <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>

                <div class="checkout-card">
                    <h2>Billing Information</h2>

                    <label class="checkout-label">Name</label>
                    <input type="text" name="customer_name" class="input-field" placeholder="Enter your full name" value="<?php echo htmlspecialchars($customer_name); ?>" required>

                    <label class="checkout-label">Phone</label>
                    <input type="tel" name="customer_phone" class="input-field" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($customer_phone); ?>" required>

                    <label class="checkout-label">Email</label>
                    <input type="email" name="customer_email" class="input-field" placeholder="Enter your email address" value="<?php echo htmlspecialchars($customer_email); ?>" required>

                    <label class="checkout-label">Shipping Address</label>
                    <textarea name="shipping_address" class="input-field" rows="4" placeholder="Enter your shipping address" required><?php echo htmlspecialchars($shipping_address); ?></textarea>

                    <p class="checkout-note" style="margin-bottom:18px;">
                        Payment Method: <strong>Cash on Delivery</strong>
                    </p>

                    <button type="submit" name="checkout" class="btn">
                        Place Order
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
function updateRowTotals(inputElement) {
    let qty = parseInt(inputElement.value);
    if (isNaN(qty) || qty < 1) {
        qty = 1;
        inputElement.value = 1;
    }

    const row = inputElement.closest('.cart-row');
    const id = row.getAttribute('data-id');
    const price = parseFloat(row.getAttribute('data-price'));
    const subtotal = price * qty;

    // Update row subtotal display
    row.querySelector('.subtotal-cell span').textContent = subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

    // Update matching item in Order Summary card
    const summaryRow = document.getElementById('summary-item-' + id);
    if (summaryRow) {
        summaryRow.querySelector('.summary-item-qty').textContent = qty;
        summaryRow.querySelector('.summary-item-subtotal').textContent = 'Tk. ' + subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Recalculate Grand Total
    let grandTotal = 0;
    document.querySelectorAll('.cart-row').forEach(r => {
        const itemPrice = parseFloat(r.getAttribute('data-price'));
        const itemQty = parseInt(r.querySelector('.qty-input').value) || 1;
        grandTotal += itemPrice * itemQty;
    });

    document.getElementById('grand-total-display').textContent = 'Tk. ' + grandTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}
</script>
</body>
</html>
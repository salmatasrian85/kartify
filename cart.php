<?php
session_start();
include "db.php";

function ensure_single_order_customer_columns($conn) {
    $columns = [
        'customer_name' => 'VARCHAR(255) NULL',
        'customer_email' => 'VARCHAR(255) NULL',
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
$message = "";
$errors = [];

if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($cart[$remove_id])) {
        unset($cart[$remove_id]);
        $_SESSION['cart'] = $cart;
    }
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_qty'])) {
        foreach ($_POST['qty'] as $product_id => $qty) {
            $product_id = intval($product_id);
            $qty = max(1, intval($qty));
            if ($qty > 0) {
                $cart[$product_id] = $qty;
            }
        }
        $_SESSION['cart'] = $cart;
        header("Location: cart.php");
        exit();
    }

    if (isset($_POST['checkout'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        if (empty($cart)) {
            $errors[] = "Your cart is empty. Add products before checkout.";
        }

        $payment_method = $_POST['payment_method'] ?? '';
        if (!in_array($payment_method, ['cashon', 'mfs'])) {
            $errors[] = "Please select a payment method.";
        }

        $mfs_number = '';
        if ($payment_method === 'mfs') {
            $mfs_number = trim($_POST['mfs_number'] ?? '');
            if ($mfs_number === '') {
                $errors[] = "Please enter your MFS account number.";
            }
        }

        if (empty($errors)) {
            $product_ids = array_map('intval', array_keys($cart));
            $id_list = implode(',', $product_ids);
            $sql = "SELECT * FROM products WHERE id IN ($id_list)";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_id = intval($row['id']);
                    if (!isset($cart[$product_id])) {
                        continue;
                    }
                    $quantity = intval($cart[$product_id]);
                    $row['quantity'] = $quantity;
                    $row['subtotal'] = $row['price'] * $quantity;
                    $cart_items[$product_id] = $row;
                    $total_amount += $row['subtotal'];
                }
            }

            if (empty($cart_items)) {
                $errors[] = "Cart items could not be loaded. Please try again.";
            }

            $user_id = intval($_SESSION['user_id']);
            $user_result = mysqli_query($conn, "SELECT name, email FROM users WHERE id = '$user_id'");
            $user_data = $user_result ? mysqli_fetch_assoc($user_result) : [];
            $customer_name = mysqli_real_escape_string($conn, $user_data['name'] ?? '');
            $customer_email = mysqli_real_escape_string($conn, $user_data['email'] ?? '');
            ensure_single_order_customer_columns($conn);

            if (empty($errors)) {
                foreach ($cart_items as $item) {
                    if ($item['quantity'] > intval($item['stock'])) {
                        $errors[] = "Product '{$item['name']}' does not have enough stock.";
                        break;
                    }
                }
            }

            if (empty($errors)) {
                foreach ($cart_items as $item) {
                    $product_id = intval($item['id']);
                    $quantity = intval($item['quantity']);
                    $order_amount = $item['subtotal'];

                    $sql_order = "INSERT INTO single_order (user_id, product_id, total_amount, customer_name, customer_email) VALUES ('$user_id', '$product_id', '$order_amount', '$customer_name', '$customer_email')";
                    if (!mysqli_query($conn, $sql_order)) {
                        $errors[] = "Failed to save order for '{$item['name']}': " . mysqli_error($conn);
                        break;
                    }

                    $order_id = mysqli_insert_id($conn);
                    $sql_payment = "INSERT INTO payments (order_id, user_id, total_amount, payment_method) VALUES ('$order_id', '$user_id', '$order_amount', '$payment_method')";
                    if (!mysqli_query($conn, $sql_payment)) {
                        $errors[] = "Failed to save payment for '{$item['name']}': " . mysqli_error($conn);
                        break;
                    }

                    $sql_stock = "UPDATE products SET stock = stock - '$quantity' WHERE id = '$product_id'";
                    if (!mysqli_query($conn, $sql_stock)) {
                        $errors[] = "Failed to update stock for '{$item['name']}': " . mysqli_error($conn);
                        break;
                    }
                }
            }

            if (empty($errors)) {
                unset($_SESSION['cart']);
                header("Location: myorders.php");
                exit();
            }
        }
    }
}

if (!empty($cart)) {
    $product_ids = array_map('intval', array_keys($cart));
    $id_list = implode(',', $product_ids);
    $sql = "SELECT * FROM products WHERE id IN ($id_list)";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = intval($row['id']);
            $quantity = intval($cart[$product_id]);
            $subtotal = $row['price'] * $quantity;
            $row['quantity'] = $quantity;
            $row['subtotal'] = $subtotal;
            $cart_items[$product_id] = $row;
            $total_amount += $subtotal;
        }
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
.summary-item.total { font-weight:700; font-size:18px; }
.checkout-card h2 { margin-bottom:18px; font-size:22px; }
.method-group { display:grid; gap:12px; margin-bottom:18px; }
.method-group label { display:flex; align-items:center; gap:12px; cursor:pointer; }
.method-group input[type="radio"] { accent-color:#111; }
.input-field { width:100%; padding:12px 14px; border-radius:10px; border:1px solid #ddd; outline:none; }
.btn { width:100%; padding:14px 18px; border:none; border-radius:12px; background:#111; color:white; font-size:16px; cursor:pointer; transition:0.3s; }
.btn:hover { background:#333; }
.notice { margin-bottom:20px; padding:14px 16px; border-radius:12px; }
.notice.error { background:#fdecea; color:#b21b2d; }
.notice.success { background:#e9f9ee; color:#1b6c3f; }
.empty-state { background:white; padding:40px; border-radius:16px; text-align:center; box-shadow:0 10px 28px rgba(0,0,0,0.05); }
.empty-state a { margin-top:18px; display:inline-block; text-decoration:none; color:white; background:#111; padding:12px 24px; border-radius:10px; }
@media(max-width:860px) { .summary { grid-template-columns:1fr; } .product-name { flex-direction:column; align-items:flex-start; } .cart-table th, .cart-table td { padding:14px 12px; } }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1>Shopping Cart</h1>
            <p style="color:#555; margin-top:8px;">Review your selected art pieces and choose checkout.</p>
        </div>
        <a href="index.php"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
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
                        <tr>
                            <td>
                                <div class="product-name">
                                    <img src="image/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                        <span style="color:#777;"><?php echo htmlspecialchars($item['category_name']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>৳ <?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input class="qty-input" type="number" name="qty[<?php echo intval($item['id']); ?>]" value="<?php echo intval($item['quantity']); ?>" min="1">
                            </td>
                            <td>৳ <?php echo number_format($item['subtotal'], 2); ?></td>
                            <td><a class="action-link" href="cart.php?remove=<?php echo intval($item['id']); ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-card">
                    <h2>Order Summary</h2>
                    <div class="summary-item">
                        <span>Items total</span>
                        <span>৳ <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <div class="summary-item total">
                        <span>Total</span>
                        <span>৳ <?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <button type="submit" name="update_qty" class="btn" style="margin-top:10px;">Update Quantities</button>
                </div>

                <div class="checkout-card">
                    <h2>Checkout</h2>
                    <div class="method-group">
                        <label>
                            <input type="radio" name="payment_method" value="cashon" <?php echo (!isset($_POST['payment_method']) || $_POST['payment_method'] === 'cashon') ? 'checked' : ''; ?> >
                            Cash on Delivery
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="mfs" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'mfs') ? 'checked' : ''; ?> >
                            Mobile Financial Service (bKash / Nagad)
                        </label>
                    </div>
                    <div>
                        <label for="mfs_number" style="display:block; margin-bottom:10px; font-weight:600;">MFS Account Number</label>
                        <input class="input-field" type="text" id="mfs_number" name="mfs_number" placeholder="Enter bKash or Nagad number" value="<?php echo isset($_POST['mfs_number']) ? htmlspecialchars($_POST['mfs_number']) : ''; ?>">
                    </div>
                    <button type="submit" name="checkout" class="btn" style="margin-top:18px;">Place Order</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
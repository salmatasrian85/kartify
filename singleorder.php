<?php
// Start the session so we know who is logged in
session_start();

// Connect to the database using the db.php file
include "db.php";

// Check if the user is logged in. If they are not logged in, send them to login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Stop running any more code on this page
}

// Check if a product_id is sent in the URL line
if (isset($_GET['product_id'])) {

    // Get the data from the URL and save them into clean variables
    $product_id = intval($_GET['product_id']); // intval makes sure it is a safe number
    $user_id = intval($_SESSION['user_id']);   // ensure it's treated as an integer
    
    // Check if a payment method is sent in the URL line
    if (isset($_GET['method'])) {
        // If yes, clean the text and save it
        $payment_method = mysqli_real_escape_string($conn, $_GET['method']);
    } else {
        // If no method is sent, set it to 'cashon' by default
        $payment_method = 'cashon';
    }

    // Step 1: Ask the database for the price and stock of this product
    $query_product = "SELECT price, stock FROM products WHERE id = '$product_id'";
    $result_product = mysqli_query($conn, $query_product);

    // If the database cannot find any product with this ID, show an error
    if (mysqli_num_rows($result_product) == 0) {
        die("Error: Product not found inside the database!");
    }

    // Turn the database result into an easy-to-use array row
    $row = mysqli_fetch_assoc($result_product);
    $price = $row['price'];
    $current_stock = $row['stock'];

    // Step 2: Check if the product has items left to sell
    if ($current_stock <= 0) {
        die("Sorry, this product is out of stock!");
    }

    // Fake payment check. In real life, you will connect a bkash/nagad gateway here.
    $payment_status = "success"; 

    if ($payment_status == "success") {

        // Step 3: Insert a new record into the single_order table
        $sql_order = "INSERT INTO single_order (user_id, product_id, total_amount)
                      VALUES ('$user_id', '$product_id', '$price')";

        if (mysqli_query($conn, $sql_order)) {

            // Grab the unique ID number of the order that we just saved above
            $order_id = mysqli_insert_id($conn); 
           
            // Step 4: Insert a record into the payments table using that order ID
            $sql_payment = "INSERT INTO payments (order_id, user_id, total_amount, payment_method) 
                            VALUES ('$order_id', '$user_id', '$price', '$payment_method')";
            
            if (mysqli_query($conn, $sql_payment)) {
                
                // Step 5: Everything is successful! Minus 1 from the item stock number
                $sql_update_stock = "UPDATE products SET stock = stock - 1 WHERE id = '$product_id'";
                
                if (mysqli_query($conn, $sql_update_stock)) {
                    // Show a nice message to the customer if everything works perfectly
                    echo "<h3>Success!</h3>";
                    echo "Order Saved with ID: " . $order_id . "<br>";
                    echo "Payment Successful, Order Placed <br>";
                    echo "Amount Paid: " . $price . " ৳<br>";
                    echo "Stock updated successfully.<br>";
                    echo "<a href='index.php'>Back to Shop</a>";
                } else {
                    echo "Order and Payment saved, but Stock Update Error: " . mysqli_error($conn);
                }
                
            } else {
                // Show this error if the data fails to save in the payments table
                echo "Order placed, but Payment Logging Error: " . mysqli_error($conn);
            }

        } else {
            // Show this error if the data fails to save in the single_order table
            echo "Order Insertion Error: " . mysqli_error($conn);
        }

    } else {
        // Show this message if the payment status is not "success"
        echo "Payment Failed. Try again.";
    }

} else {
    // If someone visits this page directly without selecting a product, send them to index.php
    header("Location: index.php");
}
?>
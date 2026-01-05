<?php
session_start();
require('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Save Customer
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    $sql_cust = "INSERT INTO customers (name, email, phone, address, city, state, pincode) 
                 VALUES ('$name', '$email', '$phone', '$address', '$city', '$state', '$pincode')";
    
    if (mysqli_query($conn, $sql_cust)) {
        $customer_id = mysqli_insert_id($conn);
    } else {
        die("Error saving customer: " . mysqli_error($conn));
    }

    // 2. Save Order
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];
    
    $payment_status = 'pending';
    if ($payment_method == 'razorpay' && isset($_POST['razorpay_payment_id'])) {
        $payment_status = 'paid';
    }

    $sql_order = "INSERT INTO orders (customer_id, total_amount, payment_method, payment_status, order_status) 
                  VALUES ('$customer_id', '$total_amount', '$payment_method', '$payment_status', 'pending')";
    
    if (mysqli_query($conn, $sql_order)) {
        $order_id = mysqli_insert_id($conn);
    } else {
        die("Error saving order: " . mysqli_error($conn));
    }

    // 3. Save Order Items
    foreach ($_SESSION['cart'] as $prod_id => $qty) {
        $sql_prod = "SELECT name, price FROM products WHERE id='$prod_id'";
        $res_prod = mysqli_query($conn, $sql_prod);
        $prod_data = mysqli_fetch_assoc($res_prod);

        $p_name = mysqli_real_escape_string($conn, $prod_data['name']);
        $p_price = $prod_data['price'];
        $subtotal = $p_price * $qty;

        $sql_item = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) 
                     VALUES ('$order_id', '$prod_id', '$p_name', '$p_price', '$qty', '$subtotal')";
        mysqli_query($conn, $sql_item);
    }

    // 4. Clear Cart
    unset($_SESSION['cart']);

    // 5. Redirect
    header("Location: success.php?orderid=" . $order_id);
    exit();

} else {
    header("Location: cart.php");
    exit();
}
?>

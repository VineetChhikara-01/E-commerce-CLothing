<?php
require('auth_session.php');
require('../db_connect.php');

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];

// Fetch Order & Customer Details
$sql_order = "SELECT o.*, c.* 
              FROM orders o 
              JOIN customers c ON o.customer_id = c.id 
              WHERE o.id = $order_id";
$result_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($result_order);

if (!$order) {
    die("Order not found.");
}

// Fetch Order Items
$sql_items = "SELECT * FROM order_items WHERE order_id = $order_id";
$result_items = mysqli_query($conn, $sql_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?php echo $order_id; ?> Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #eee; padding: 10px; text-align: center; margin-bottom: 20px; }
        .admin-nav a { margin: 0 15px; font-weight: bold; color: #333; }
        .order-box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>

<div class="admin-nav">
    <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
    <a href="upload-product.php">Upload Product</a>
    <a href="orders.php">View Orders</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <a href="orders.php" class="btn">&laquo; Back to Orders</a>
    <h2 style="margin: 20px 0;">Order Details #<?php echo $order['id']; ?></h2>

    <div class="order-box">
        <div class="details-grid">
            <div>
                <h3>Customer Details</h3>
                <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
                <p><strong>Address:</strong> <?php echo $order['address'] . ", " . $order['city'] . ", " . $order['state'] . " - " . $order['pincode']; ?></p>
            </div>
            <div>
                <h3>Order Info</h3>
                <p><strong>Date:</strong> <?php echo date("d M Y, h:i A", strtotime($order['order_date'])); ?></p>
                <p><strong>Payment Method:</strong> <?php echo strtoupper($order['payment_method']); ?></p>
                <p><strong>Payment Status:</strong> <?php echo ucfirst($order['payment_status']); ?></p>
                <p><strong>Order Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
            </div>
        </div>

        <h3 style="margin-top: 30px;">Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($item = mysqli_fetch_assoc($result_items)) {
                    echo "<tr>";
                    echo "<td>" . $item['product_name'] . "</td>";
                    echo "<td>₹" . $item['price'] . "</td>";
                    echo "<td>" . $item['quantity'] . "</td>";
                    echo "<td>₹" . $item['subtotal'] . "</td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Grand Total:</td>
                    <td style="font-weight: bold;">₹<?php echo $order['total_amount']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

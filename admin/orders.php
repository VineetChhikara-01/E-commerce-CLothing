<?php
require('auth_session.php');
require('../db_connect.php');

$sql = "SELECT o.*, c.name as customer_name, c.email 
        FROM orders o 
        JOIN customers c ON o.customer_id = c.id 
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #eee; padding: 10px; text-align: center; margin-bottom: 20px; }
        .admin-nav a { margin: 0 15px; font-weight: bold; color: #333; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .status-paid { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-cod { color: blue; font-weight: bold; }
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
    <h2>All Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>#" . $row['id'] . "</td>";
                    echo "<td>" . $row['customer_name'] . "<br><small>" . $row['email'] . "</small></td>";
                    echo "<td>₹" . $row['total_amount'] . "</td>";
                    echo "<td>" . strtoupper($row['payment_method']) . "</td>";
                    
                    $p_status = $row['payment_status'];
                    $color = ($p_status == 'paid') ? 'green' : 'orange';
                    echo "<td style='color:$color; font-weight:bold;'>" . ucfirst($p_status) . "</td>";

                    echo "<td>" . ucfirst($row['order_status']) . "</td>";
                    echo "<td>" . date("d M Y, h:i A", strtotime($row['order_date'])) . "</td>";
                    echo "<td><a href='order-details.php?id=" . $row['id'] . "' class='btn btn-primary' style='padding:5px 10px; font-size:12px;'>View</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center;'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

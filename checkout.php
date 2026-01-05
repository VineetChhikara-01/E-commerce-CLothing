<?php
require 'db_connect.php';
$page_css = 'checkout.css';
require 'includes/header.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Calculate Total
$total_amount = 0;
$total_items = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $sql = "SELECT price FROM products WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    $total_amount += $product['price'] * $qty;
    $total_items += $qty;
}
?>

<div class="container checkout-container">
    <h2>Checkout</h2>
    <div class="checkout-wrapper">
        <!-- Left: Customer Form -->
        <div class="checkout-form-section">
            <form action="place_order.php" method="post" id="checkoutForm">
                <h3>Billing Details</h3>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address (Optional)">
                <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}" maxlength="10"
                    title="Please enter a valid phone number">
                <textarea name="address" placeholder="Shipping Address" required></textarea>
                <div class="checkout-form-row">
                    <input type="text" name="city" placeholder="City" required>
                    <input type="text" name="state" placeholder="State" required>
                    <input type="text" name="pincode" placeholder="Pincode" inputmode="numeric" required pattern="[0-9]+" title="Please enter a valid pincode">
                </div>

                <h3>Payment Method</h3>
                <div class="checkout-payment-options">
                    <label>
                        <input type="radio" name="payment_method" value="cod" checked onchange="togglePayment('cod')">
                        Cash on Delivery (COD)
                    </label>
                    <br><br>
                    <label>
                        <input type="radio" name="payment_method" value="razorpay" onchange="togglePayment('razorpay')">
                        Pay Online (Razorpay)
                    </label>
                </div>

                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

                <button type="submit" name="place_order" id="codBtn" class="btn btn-success checkout-submit-btn">Place
                    Order (COD)</button>

                <!-- Razorpay Button Placeholder -->
                <button type="button" id="rzpBtn" class="btn btn-primary razorpay-btn" onclick="startRazorpay()">Pay
                    Now</button>
            </form>
        </div>

        <!-- Right: Order Summary -->
        <div class="checkout-summary-section">
            <h3>Order Summary</h3>
            <p><strong>Total Items:</strong> <?php echo $total_items; ?></p>
            <hr>
            <h2 class="checkout-total-price">Total: ₹<?php echo $total_amount; ?></h2>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function togglePayment(method) {
        if (method === 'cod') {
            document.getElementById('codBtn').style.display = 'block';
            document.getElementById('rzpBtn').style.display = 'none';
        } else {
            document.getElementById('codBtn').style.display = 'none';
            document.getElementById('rzpBtn').style.display = 'block';
        }
    }

    function startRazorpay() {
        var options = {
            "key": "YOUR_RAZORPAY_KEY_ID", // Enter the Key ID generated from the Dashboard
            "amount": "<?php echo $total_amount * 100; ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            "currency": "INR",
            "name": "Clothing Shop",
            "description": "Purchase",
            "handler": function (response) {
                // Submit form with payment ID
                var form = document.getElementById('checkoutForm');
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'razorpay_payment_id';
                input.value = response.razorpay_payment_id;
                form.appendChild(input);

                // Change payment method to razorpay and submit
                // Since the user selected razorpay, the radio is already set.
                // We just need to trigger the submit handler which usually is for COD, 
                // but we can add a hidden input to signal valid payment.

                // Actually simpler: just submit the form now.
                // We need to make sure the backend knows it's razorpay and verified.
                form.submit();
            },
            "prefill": {
                "name": document.getElementsByName('name')[0].value,
                "email": document.getElementsByName('email')[0].value,
                "contact": document.getElementsByName('phone')[0].value
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        // Basic validation
        if (document.getElementsByName('name')[0].value === "" || document.getElementsByName('email')[0].value === "") {
            alert("Please fill in your details first.");
            return;
        }

        var rzp1 = new Razorpay(options);
        rzp1.open();
    }
</script>

<?php require('includes/footer.php'); ?>
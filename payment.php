<?php
require_once 'config.php';

if(!isLoggedIn()) {
    redirect('login.php');
}

if(!isset($_GET['booking_id'])) {
    redirect('dashboard.php');
}

$booking_id = (int)$_GET['booking_id'];

$db = new Database();
$conn = $db->connect();

// Get booking details
$stmt = $conn->prepare("
    SELECT b.*, c.model_name, c.series, u.full_name, u.email, u.phone
    FROM bookings b 
    JOIN cars c ON b.car_id = c.car_id 
    JOIN users u ON b.user_id = u.user_id
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if(!$booking) {
    showError('Booking not found');
    redirect('dashboard.php');
}

// Check if already paid
if($booking['payment_status'] == 'paid') {
    showSuccess('Payment already completed for this booking');
    redirect('booking_details.php?id=' . $booking_id);
}

// Razorpay Configuration (Replace with your actual keys)
$razorpay_key_id = "rzp_test_YOUR_KEY_ID"; // Get from Razorpay Dashboard
$razorpay_key_secret = "YOUR_KEY_SECRET";  // Keep this secret!

// Generate unique receipt ID
$receipt_id = "TITANS_" . $booking_id . "_" . time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="payment-section">
        <div class="container">
            <div style="margin-bottom: 2rem;">
                <a href="booking_details.php?id=<?php echo $booking_id; ?>" class="btn btn-secondary">← Back to Booking</a>
            </div>
            
            <h1 class="section-title">Complete Payment</h1>
            
            <?php if($error = getError()): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="payment-grid">
                <!-- Payment Summary -->
                <div class="payment-card">
                    <h2>Payment Summary</h2>
                    
                    <div class="booking-summary-box">
                        <div class="summary-header">
                            <h3>Booking #<?php echo $booking['booking_id']; ?></h3>
                            <span class="badge badge-pending">Payment Pending</span>
                        </div>
                        
                        <div class="car-summary">
                            <div class="car-icon-large">🚗</div>
                            <div>
                                <h4><?php echo htmlspecialchars($booking['model_name']); ?></h4>
                                <p><?php echo htmlspecialchars($booking['series']); ?></p>
                            </div>
                        </div>
                        
                        <div class="summary-details">
                            <div class="detail-row">
                                <span>Pickup Date:</span>
                                <strong><?php echo date('d M Y', strtotime($booking['pickup_date'])); ?></strong>
                            </div>
                            <div class="detail-row">
                                <span>Return Date:</span>
                                <strong><?php echo date('d M Y', strtotime($booking['return_date'])); ?></strong>
                            </div>
                            <div class="detail-row">
                                <span>Total Days:</span>
                                <strong><?php echo $booking['total_days']; ?> days</strong>
                            </div>
                            <div class="detail-row">
                                <span>Pickup Location:</span>
                                <strong><?php echo htmlspecialchars($booking['pickup_location']); ?></strong>
                            </div>
                        </div>
                        
                        <div class="price-summary">
                            <div class="price-row">
                                <span>Rental Charges</span>
                                <span>₹<?php echo number_format($booking['total_price']); ?></span>
                            </div>
                            <div class="price-row">
                                <span>GST (18%)</span>
                                <span>₹<?php echo number_format($booking['total_price'] * 0.18); ?></span>
                            </div>
                            <div class="price-row">
                                <span>Security Deposit (Refundable)</span>
                                <span>₹5,000</span>
                            </div>
                            <div class="price-row total">
                                <strong>Total Amount</strong>
                                <strong>₹<?php echo number_format($booking['total_price'] + ($booking['total_price'] * 0.18) + 5000); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="payment-card">
                    <h2>Choose Payment Method</h2>
                    
                    <div class="payment-methods">
                        <!-- Razorpay Payment -->
                        <div class="payment-method-card active" onclick="selectPaymentMethod('razorpay')">
                            <input type="radio" name="payment_method" value="razorpay" checked>
                            <div class="method-info">
                                <h4>💳 Card / UPI / NetBanking</h4>
                                <p>Secure payment via Razorpay</p>
                                <div class="payment-icons">
                                    <span>💳 Card</span>
                                    <span>📱 UPI</span>
                                    <span>🏦 NetBanking</span>
                                    <span>💰 Wallet</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pay at Pickup (Alternative) -->
                        <div class="payment-method-card" onclick="selectPaymentMethod('pickup')">
                            <input type="radio" name="payment_method" value="pickup">
                            <div class="method-info">
                                <h4>🏢 Pay at Pickup</h4>
                                <p>Pay when you collect the car (Subject to availability)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="security-info">
                        <h4>🔒 Secure Payment</h4>
                        <p>Your payment information is encrypted and secure. We never store your card details.</p>
                    </div>
                    
                    <button type="button" id="payButton" class="btn btn-primary btn-block btn-large" onclick="processPayment()">
                        Proceed to Pay ₹<?php echo number_format($booking['total_price'] + ($booking['total_price'] * 0.18) + 5000); ?>
                    </button>
                    
                    <div class="terms-info">
                        <p>By proceeding, you agree to our <a href="#">Terms & Conditions</a> and <a href="#">Cancellation Policy</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script>
        let selectedPaymentMethod = 'razorpay';
        
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            document.querySelector(`input[value="${method}"]`).checked = true;
        }
        
        function processPayment() {
            if(selectedPaymentMethod === 'razorpay') {
                initiateRazorpayPayment();
            } else if(selectedPaymentMethod === 'pickup') {
                payAtPickup();
            }
        }
        
        function initiateRazorpayPayment() {
            const totalAmount = <?php echo ($booking['total_price'] + ($booking['total_price'] * 0.18) + 5000) * 100; ?>; // Amount in paise
            
            const options = {
                "key": "<?php echo $razorpay_key_id; ?>",
                "amount": totalAmount,
                "currency": "INR",
                "name": "TITAN'S DRIVE",
                "description": "Booking #<?php echo $booking['booking_id']; ?> - <?php echo htmlspecialchars($booking['model_name']); ?>",
                "image": "https://your-logo-url.com/logo.png",
                "order_id": "", // Generate from server for better security
                "handler": function (response){
                    // Payment successful
                    verifyPayment(response);
                },
                "prefill": {
                    "name": "<?php echo htmlspecialchars($booking['full_name']); ?>",
                    "email": "<?php echo htmlspecialchars($booking['email']); ?>",
                    "contact": "<?php echo htmlspecialchars($booking['phone']); ?>"
                },
                "notes": {
                    "booking_id": "<?php echo $booking['booking_id']; ?>",
                    "user_id": "<?php echo $_SESSION['user_id']; ?>"
                },
                "theme": {
                    "color": "#0066b1"
                },
                "modal": {
                    "ondismiss": function(){
                        alert('Payment cancelled. You can try again.');
                    }
                }
            };
            
            const rzp = new Razorpay(options);
            rzp.open();
        }
        
        function verifyPayment(response) {
            // Send payment details to server for verification
            fetch('verify_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                    booking_id: <?php echo $booking_id; ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Payment Successful! ✅');
                    window.location.href = 'booking_details.php?id=<?php echo $booking_id; ?>';
                } else {
                    alert('Payment verification failed. Please contact support.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please contact support.');
            });
        }
        
        function payAtPickup() {
            if(confirm('You have selected "Pay at Pickup". Your booking will remain pending until payment is received. Continue?')) {
                // Update booking with payment method
                fetch('update_payment_method.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        booking_id: <?php echo $booking_id; ?>,
                        payment_method: 'pickup'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Booking confirmed! Payment will be collected at pickup.');
                        window.location.href = 'booking_details.php?id=<?php echo $booking_id; ?>';
                    }
                });
            }
        }
    </script>
    
    <style>
        .payment-section {
            padding: 3rem 0;
            background: var(--light-color);
            min-height: calc(100vh - 70px);
        }
        
        .payment-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .payment-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .payment-card h2 {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
        }
        
        .booking-summary-box {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: 10px;
        }
        
        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #ddd;
        }
        
        .car-summary {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #fff;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .car-icon-large {
            font-size: 3rem;
        }
        
        .car-summary h4 {
            margin-bottom: 0.3rem;
            color: var(--dark-color);
        }
        
        .car-summary p {
            color: var(--gray-color);
            margin: 0;
        }
        
        .summary-details {
            margin-bottom: 1.5rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #ddd;
        }
        
        .price-summary {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #ddd;
        }
        
        .price-row.total {
            border-top: 2px solid var(--bmw-blue);
            border-bottom: none;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.3rem;
            color: var(--bmw-blue);
        }
        
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .payment-method-card {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method-card:hover {
            border-color: var(--bmw-blue);
            background: var(--light-color);
        }
        
        .payment-method-card.active {
            border-color: var(--bmw-blue);
            background: #e3f2fd;
        }
        
        .payment-method-card input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-top: 5px;
        }
        
        .method-info h4 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }
        
        .method-info p {
            color: var(--gray-color);
            margin-bottom: 0.8rem;
        }
        
        .payment-icons {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        
        .payment-icons span {
            background: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
            border: 1px solid #ddd;
        }
        
        .security-info {
            background: #e8f5e9;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid var(--success-color);
        }
        
        .security-info h4 {
            margin-bottom: 0.5rem;
            color: var(--success-color);
        }
        
        .security-info p {
            margin: 0;
            color: #2e7d32;
        }
        
        .btn-large {
            padding: 1.2rem 2rem;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .terms-info {
            text-align: center;
            margin-top: 1rem;
        }
        
        .terms-info p {
            font-size: 0.85rem;
            color: var(--gray-color);
        }
        
        .terms-info a {
            color: var(--bmw-blue);
            text-decoration: none;
        }
        
        @media (max-width: 768px) {
            .payment-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
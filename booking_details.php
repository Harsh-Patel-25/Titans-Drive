<?php
require_once 'config.php';

if(!isLoggedIn()) {
    redirect('login.php');
}

if(!isset($_GET['id'])) {
    redirect('dashboard.php');
}

$booking_id = (int)$_GET['id'];

$db = new Database();
$conn = $db->connect();

// Get booking details
$stmt = $conn->prepare("
    SELECT b.*, c.model_name, c.series, c.year, c.color, c.transmission, 
           c.fuel_type, c.seats, c.mileage, c.engine, c.features, c.image_url AS car_image,
           u.full_name, u.email, u.phone
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

// Handle cancellation
if(isset($_POST['cancel_booking'])) {
    if($booking['booking_status'] == 'pending' || $booking['booking_status'] == 'confirmed') {
        $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'cancelled' WHERE booking_id = ?");
        if($stmt->execute([$booking_id])) {
            showSuccess('Booking cancelled successfully');
            redirect('dashboard.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="booking-details-section">
        <div class="container">
            <div style="margin-bottom: 2rem;">
                <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
            
            <h1 class="section-title">Booking Details</h1>
            
            <?php if($error = getError()): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success = getSuccess()): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="booking-details-grid">
                <!-- Booking Information -->
                <div class="details-card">
                    <div class="card-header">
                        <h2>Booking Information</h2>
                        <span class="booking-id">Booking #<?php echo $booking['booking_id']; ?></span>
                    </div>
                    
                    <div class="status-banner status-<?php echo $booking['booking_status']; ?>">
                        <span class="status-icon">
                            <?php
                            switch($booking['booking_status']) {
                                case 'pending': echo '⏳'; break;
                                case 'confirmed': echo '✓'; break;
                                case 'completed': echo '🎉'; break;
                                case 'cancelled': echo '✗'; break;
                            }
                            ?>
                        </span>
                        <div class="status-text">
                            <strong><?php echo ucfirst($booking['booking_status']); ?></strong>
                            <p>
                                <?php
                                switch($booking['booking_status']) {
                                    case 'pending': echo 'Your booking is awaiting confirmation'; break;
                                    case 'confirmed': echo 'Your booking has been confirmed!'; break;
                                    case 'completed': echo 'Thank you for choosing TITAN\'S DRIVE'; break;
                                    case 'cancelled': echo 'This booking has been cancelled'; break;
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Booking Date:</span>
                            <span class="info-value"><?php echo date('d M Y, H:i', strtotime($booking['created_at'])); ?></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Pickup Date:</span>
                            <span class="info-value"><strong><?php echo date('d M Y', strtotime($booking['pickup_date'])); ?></strong></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Return Date:</span>
                            <span class="info-value"><strong><?php echo date('d M Y', strtotime($booking['return_date'])); ?></strong></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Pickup Location:</span>
                            <span class="info-value"><?php echo htmlspecialchars($booking['pickup_location']); ?></span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Total Days:</span>
                            <span class="info-value"><?php echo $booking['total_days']; ?> days</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Payment Status:</span>
                            <span class="info-value">
                                <span class="badge badge-<?php echo $booking['payment_status'] == 'paid' ? 'completed' : 'pending'; ?>">
                                    <?php echo ucfirst($booking['payment_status']); ?>
                                </span>
                            </span>
                        </div>
                        
                        <?php if($booking['special_requests']): ?>
                        <div class="info-row" style="grid-column: 1 / -1;">
                            <span class="info-label">Special Requests:</span>
                            <span class="info-value"><?php echo htmlspecialchars($booking['special_requests']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <h3>Price Breakdown</h3>
                        <div class="price-row">
                            <span>Car Rental (<?php echo $booking['total_days']; ?> days)</span>
                            <span>₹<?php echo number_format($booking['total_price']); ?></span>
                        </div>
                        <div class="price-row">
                            <span>Taxes & Fees</span>
                            <span>Included</span>
                        </div>
                        <div class="price-row total">
                            <span><strong>Total Amount</strong></span>
                            <span><strong>₹<?php echo number_format($booking['total_price']); ?></strong></span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <?php if($booking['booking_status'] == 'pending' || $booking['booking_status'] == 'confirmed'): ?>
                    <div class="booking-actions">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            <button type="submit" name="cancel_booking" class="btn btn-danger btn-block">
                                Cancel Booking
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Car Information -->
                <div class="details-card">
                    <div class="card-header">
                        <h2>Car Details</h2>
                    </div>
                    
                    <div class="car-preview">
                        <?php
                            $carImage = '';
                            if (!empty($booking['car_image'])) {
                                // if value already contains a directory separator assume it's a path, otherwise prefix uploads/cars/
                                $carImage = (strpos($booking['car_image'], '/') !== false) ? $booking['car_image'] : 'uploads/cars/' . $booking['car_image'];
                            } else {
                                $carImage = 'images/default_car.jpg';
                            }
                        ?>
                        <div class="car-image-large" style="background: url('<?php echo htmlspecialchars($carImage, ENT_QUOTES); ?>') center/cover no-repeat;">
                        </div>
                        
                        <div class="car-title">
                            <h3><?php echo htmlspecialchars($booking['model_name']); ?></h3>
                            <p><?php echo htmlspecialchars($booking['series'] . ' • ' . $booking['color']); ?></p>
                            <span class="car-year-badge"><?php echo htmlspecialchars($booking['year']); ?></span>
                        </div>
                    </div>
                    
                    <div class="car-specs-grid">
                        <div class="spec-box">
                            <div class="spec-icon">🎨</div>
                            <div class="spec-info">
                                <span class="spec-label">Color</span>
                                <span class="spec-value"><?php echo $booking['color']; ?></span>
                            </div>
                        </div>
                        
                        <div class="spec-box">
                            <div class="spec-icon">⚙️</div>
                            <div class="spec-info">
                                <span class="spec-label">Transmission</span>
                                <span class="spec-value"><?php echo $booking['transmission']; ?></span>
                            </div>
                        </div>
                        
                        <div class="spec-box">
                            <div class="spec-icon">⛽</div>
                            <div class="spec-info">
                                <span class="spec-label">Fuel Type</span>
                                <span class="spec-value"><?php echo $booking['fuel_type']; ?></span>
                            </div>
                        </div>
                        
                        <div class="spec-box">
                            <div class="spec-icon">👥</div>
                            <div class="spec-info">
                                <span class="spec-label">Seats</span>
                                <span class="spec-value"><?php echo $booking['seats']; ?> Passengers</span>
                            </div>
                        </div>
                        
                        <div class="spec-box">
                            <div class="spec-icon">📊</div>
                            <div class="spec-info">
                                <span class="spec-label">Mileage</span>
                                <span class="spec-value"><?php echo $booking['mileage']; ?></span>
                            </div>
                        </div>
                        
                        <div class="spec-box">
                            <div class="spec-icon">🔧</div>
                            <div class="spec-info">
                                <span class="spec-label">Engine</span>
                                <span class="spec-value"><?php echo $booking['engine']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($booking['features']): ?>
                    <div class="features-section">
                        <h4>Features</h4>
                        <div class="features-tags">
                            <?php 
                            $features = explode(',', $booking['features']);
                            foreach($features as $feature): 
                            ?>
                                <span class="feature-tag">✓ <?php echo trim($feature); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="contact-support">
                        <h4>Need Help?</h4>
                        <p>Contact our support team for any queries</p>
                        <div class="support-info">
                            <p>📞 +91 98765 43210</p>
                            <p>📧 support@titansdrive.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Important Information -->
            <div class="details-card mt-3">
                <h3>Important Information</h3>
                <ul class="info-list">
                    <li>Please carry valid driving license and ID proof at the time of pickup</li>
                    <li>The car should be returned at the same pickup location unless otherwise arranged</li>
                    <li>Fuel charges are not included in the rental price</li>
                    <li>Any damages to the vehicle will be charged as per company policy</li>
                    <li>Late return charges: ₹500 per hour after the scheduled return time</li>
                    <li>For cancellation, please cancel at least 24 hours before pickup time</li>
                </ul>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    
    <style>
        .booking-details-section {
            padding: 3rem 0;
            background: var(--light-color);
            min-height: calc(100vh - 70px);
        }
        
        .booking-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .details-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-color);
        }
        
        .card-header h2 {
            color: var(--dark-color);
            margin: 0;
        }
        
        .booking-id {
            background: var(--bmw-blue);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .status-banner {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .status-banner.status-pending {
            background: #fff3cd;
            border-left: 4px solid var(--warning-color);
        }
        
        .status-banner.status-confirmed {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
        }
        
        .status-banner.status-completed {
            background: #d4edda;
            border-left: 4px solid var(--success-color);
        }
        
        .status-banner.status-cancelled {
            background: #f8d7da;
            border-left: 4px solid var(--danger-color);
        }
        
        .status-icon {
            font-size: 2.5rem;
        }
        
        .status-text strong {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }
        
        .status-text p {
            margin: 0;
            color: var(--gray-color);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .info-row {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 8px;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: var(--gray-color);
            font-weight: 500;
        }
        
        .info-value {
            font-size: 1rem;
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .price-breakdown {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .price-breakdown h3 {
            margin-bottom: 1rem;
            color: var(--dark-color);
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
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-size: 1.2rem;
            color: var(--bmw-blue);
        }
        
        .car-preview {
            margin-bottom: 2rem;
        }
        
        .car-image-large {
            height: 250px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1rem;
        }
        
        .car-year-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.95);
            color: var(--dark-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
        }
        
        .car-title {
            text-align: center;
        }
        
        .car-title h3 {
            font-size: 1.8rem;
            margin-bottom: 0.3rem;
            color: var(--dark-color);
        }
        
        .car-title p {
            color: var(--gray-color);
            font-size: 1.1rem;
        }
        
        .car-specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .spec-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 8px;
        }
        
        .spec-icon {
            font-size: 2rem;
        }
        
        .spec-info {
            display: flex;
            flex-direction: column;
        }
        
        .spec-label {
            font-size: 0.85rem;
            color: var(--gray-color);
        }
        
        .spec-value {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .features-section {
            margin-bottom: 2rem;
        }
        
        .features-section h4 {
            margin-bottom: 1rem;
            color: var(--dark-color);
        }
        
        .features-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .feature-tag {
            background: var(--bmw-blue);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .contact-support {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .contact-support h4 {
            margin-bottom: 0.5rem;
        }
        
        .contact-support p {
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .support-info p {
            margin: 0.5rem 0;
            font-weight: 600;
        }
        
        .info-list {
            list-style: none;
            padding: 0;
        }
        
        .info-list li {
            padding: 0.8rem 0;
            padding-left: 2rem;
            position: relative;
            border-bottom: 1px solid var(--light-color);
        }
        
        .info-list li:before {
            content: "ℹ️";
            position: absolute;
            left: 0;
        }
        
        @media (max-width: 768px) {
            .booking-details-grid {
                grid-template-columns: 1fr;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .car-specs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
<?php
require_once 'config.php';

if(!isset($_GET['id'])) {
    redirect('cars.php');
}

$car_id = (int)$_GET['id'];

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM cars WHERE car_id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if(!$car) {
    redirect('cars.php');
}

// Handle booking
if($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
    $pickup_date = sanitize($_POST['pickup_date']);
    $return_date = sanitize($_POST['return_date']);
    $pickup_location = sanitize($_POST['pickup_location']);
    $special_requests = sanitize($_POST['special_requests']);
    $total_days = (int)$_POST['total_days'];
    $total_price = (float)$_POST['total_price'];
    
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, pickup_date, return_date, pickup_location, total_days, total_price, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if($stmt->execute([$_SESSION['user_id'], $car_id, $pickup_date, $return_date, $pickup_location, $total_days, $total_price, $special_requests])) {
        $new_booking_id = $conn->lastInsertId();
        showSuccess('Booking request submitted successfully!');
        // Redirect to payment page
        redirect('payment.php?booking_id=' . $new_booking_id);
    } else {
        showError('Booking failed. Please try again.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['model_name']); ?> - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="car-details-section">
        <div class="container">
            <div class="car-details-grid">
                <!-- Car Image and Info -->
                <div class="car-details-left">
                    <div class="car-main-image">
                        <?php
                            // Determine image path (DB may store filename or full path)
                            if (!empty($car['image_url'])) {
                                $carImage = (strpos($car['image_url'], '/') !== false) ? $car['image_url'] : 'uploads/cars/' . $car['image_url'];
                            } else {
                                $carImage = 'images/default_car.jpg';
                            }
                        ?>
                        <div style="background-image: url('<?php echo htmlspecialchars($carImage, ENT_QUOTES); ?>'); background-size: cover; background-position: center; height: 400px; border-radius: 15px;"></div>
                        <span class="car-year-badge"><?php echo $car['year']; ?></span>
                    </div>
                    
                    <div class="car-main-info">
                        <h1><?php echo htmlspecialchars($car['model_name']); ?></h1>
                        <p class="car-series-large"><?php echo htmlspecialchars($car['series']); ?></p>
                        
                        <div class="car-price-box">
                            <div class="price-large">₹<?php echo number_format($car['price_per_day']); ?></div>
                            <div class="per-day-text">per day</div>
                        </div>
                        
                        <p class="car-description-full">
                            <?php echo htmlspecialchars($car['description']); ?>
                        </p>
                        
                        <!-- Specifications -->
                        <div class="specifications">
                            <h3>Specifications</h3>
                            <div class="specs-grid">
                                <div class="spec-item">
                                    <span class="spec-label">Year</span>
                                    <span class="spec-value"><?php echo $car['year']; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Color</span>
                                    <span class="spec-value"><?php echo $car['color']; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Transmission</span>
                                    <span class="spec-value"><?php echo $car['transmission']; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Fuel Type</span>
                                    <span class="spec-value"><?php echo $car['fuel_type']; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Seats</span>
                                    <span class="spec-value"><?php echo $car['seats']; ?> Passengers</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Mileage</span>
                                    <span class="spec-value"><?php echo $car['mileage']; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Engine</span>
                                    <span class="spec-value"><?php echo $car['engine']; ?></span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Status</span>
                                    <span class="spec-value">
                                        <span class="badge badge-<?php echo $car['availability_status']; ?>">
                                            <?php echo ucfirst($car['availability_status']); ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Features -->
                        <?php if($car['features']): ?>
                        <div class="features-box">
                            <h3>Features</h3>
                            <div class="features-list">
                                <?php 
                                $features = explode(',', $car['features']);
                                foreach($features as $feature): 
                                ?>
                                    <span class="feature-tag">✓ <?php echo trim($feature); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Booking Form -->
                <div class="car-details-right">
                    <div class="booking-card">
                        <h2>Book This Car</h2>
                        
                        <?php if($error = getError()): ?>
                            <div class="alert alert-error"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if(isLoggedIn()): ?>
                            <form method="POST" id="bookingForm">
                                <div class="form-group">
                                    <label for="pickup_date">Pickup Date *</label>
                                    <input type="date" class="form-control" id="pickup_date" name="pickup_date" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="return_date">Return Date *</label>
                                    <input type="date" class="form-control" id="return_date" name="return_date" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="pickup_location">Pickup Location *</label>
                                    <select class="form-control" id="pickup_location" name="pickup_location" required>
                                        <option value="">Select Location</option>
                                        <option value="Mumbai Airport">Mumbai Airport</option>
                                        <option value="Delhi Airport">Delhi Airport</option>
                                        <option value="Bangalore Airport">Bangalore Airport</option>
                                        <option value="Chennai Airport">Chennai Airport</option>
                                        <option value="Hyderabad Airport">Hyderabad Airport</option>
                                        <option value="City Office">City Office</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="special_requests">Special Requests</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3"></textarea>
                                </div>
                                
                                <div class="booking-summary">
                                    <h3>Booking Summary</h3>
                                    <div class="summary-row">
                                        <span>Price per day:</span>
                                        <span>₹<?php echo number_format($car['price_per_day']); ?></span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Total days:</span>
                                        <span id="total_days">0</span>
                                    </div>
                                    <div class="summary-row total">
                                        <span>Total Amount:</span>
                                        <span id="total_price">₹0</span>
                                    </div>
                                </div>
                                
                                <input type="hidden" id="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                                <input type="hidden" id="total_days_input" name="total_days">
                                <input type="hidden" id="total_price_input" name="total_price">
                                
                                <button type="submit" class="btn btn-primary btn-block">Confirm Booking</button>
                            </form>
                        <?php else: ?>
                            <div class="login-prompt">
                                <p>Please login to book this car</p>
                                <a href="login.php" class="btn btn-primary btn-block">Login</a>
                                <p class="mt-2">Don't have an account? <a href="signup.php">Sign Up</a></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    
    <style>
        .car-details-section {
            padding: 3rem 0;
            background: var(--light-color);
        }
        
        .car-details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
        }
        
        .car-main-image {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .car-year-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.95);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .car-main-info {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .car-main-info h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .car-series-large {
            font-size: 1.2rem;
            color: var(--gray-color);
            margin-bottom: 1.5rem;
        }
        
        .car-price-box {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            margin: 2rem 0;
        }
        
        .price-large {
            font-size: 3rem;
            font-weight: 700;
            color: var(--bmw-blue);
        }
        
        .per-day-text {
            color: var(--gray-color);
            font-size: 1.1rem;
        }
        
        .car-description-full {
            line-height: 1.8;
            color: var(--text-color);
            margin: 2rem 0;
        }
        
        .specifications {
            margin: 2rem 0;
        }
        
        .specifications h3 {
            margin-bottom: 1rem;
            color: var(--dark-color);
        }
        
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 8px;
        }
        
        .spec-label {
            font-weight: 600;
            color: var(--gray-color);
        }
        
        .spec-value {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .features-box {
            margin: 2rem 0;
        }
        
        .features-box h3 {
            margin-bottom: 1rem;
        }
        
        .features-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .feature-tag {
            background: var(--bmw-blue);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .booking-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 100px;
        }
        
        .booking-card h2 {
            margin-bottom: 1.5rem;
            color: var(--dark-color);
        }
        
        .booking-summary {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        
        .booking-summary h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #ddd;
        }
        
        .summary-row.total {
            border-top: 2px solid var(--bmw-blue);
            border-bottom: none;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--bmw-blue);
        }
        
        .login-prompt {
            text-align: center;
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .car-details-grid {
                grid-template-columns: 1fr;
            }
            
            .specs-grid {
                grid-template-columns: 1fr;
            }
            
            .booking-card {
                position: static;
            }
        }
    </style>
</body>
</html>
<?php
require_once 'config.php';

$db = new Database();
$conn = $db->connect();

// Get all available cars
$stmt = $conn->prepare("SELECT * FROM cars WHERE availability_status = 'available' ORDER BY model_name");
$stmt->execute();
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TITAN'S DRIVE - Premium BMW Rentals</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Martel+Sans:wght@900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>TITAN'S DRIVE</h1>
                <span class="tagline">Premium BMW Experience</span>
            </div>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="cars.php">Our Fleet</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if(isLoggedIn()): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php" class="btn-signup" style="color:white;">Sign Up</a></li>
                <?php endif; ?>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="navbot">
            <div class="strip1"></div>
            <div class="strip2"></div>
            <div class="strip3"></div>
        </div>
    </nav>
    



    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Drive Your Dreams</h1>
            <p class="hero-subtitle">Experience the ultimate luxury with premium BMW vehicles</p>
            <div class="hero-buttons">
                <a href="cars.php" class="btn btn-primary">Explore Fleet</a>
                <a href="#features" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Why Choose TITAN'S DRIVE</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🚗</div>
                    <h3>Premium Fleet</h3>
                    <p>Access to the latest BMW models from 3 Series to M5</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">✓</div>
                    <h3>Easy Booking</h3>
                    <p>Simple online reservation system with instant confirmation</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h3>Full Insurance</h3>
                    <p>Comprehensive coverage for peace of mind</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Best Prices</h3>
                    <p>Competitive rates with transparent pricing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Cars -->
    <section class="featured-cars">
        <div class="container">
            <h2 class="section-title">Featured Vehicles</h2>
            <div class="cars-grid" id="featuredCars">
                <!-- Cars will be loaded via JavaScript -->
            </div>
            <div class="text-center">
                <a href="cars.php" class="btn btn-primary">View All Cars</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>TITAN'S DRIVE</h3>
                    <p>Your premium BMW rental experience</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="cars.php">Our Fleet</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: info@titansdrive.com</p>
                    <p>Phone: +91 98765 43210</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TITAN'S DRIVE. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script>
        // Load featured cars
        document.addEventListener('DOMContentLoaded', function() {
            fetch('api/get_featured_cars.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('featuredCars');
                    if(data.success) {
                        data.cars.forEach(car => {
                            container.innerHTML += `
                                <div class="car-card">
                                    <div class="car-image" style="background: url('${car.image_url}')no-repeat center center/cover;">
                                        <span class="car-badge">${car.year}</span>
                                    </div>
                                    
                                    <div class="car-info">
                                        <h3>${car.model_name}</h3>
                                        <p class="car-series">${car.series}</p>
                                        <div class="car-specs">
                                            <span>⚙️ ${car.transmission}</span>
                                            <span>⛽ ${car.fuel_type}</span>
                                            <span>👥 ${car.seats} Seats</span>
                                        </div>
                                        <div class="car-price">
                                            <span class="price">₹${parseFloat(car.price_per_day).toLocaleString()}</span>
                                            <span class="per-day">/day</span>
                                        </div>
                                        <a href="car_details.php?id=${car.car_id}" class="btn btn-primary btn-block">View Details</a>
                                    </div>
                                </div>
                            `;
                        });
                    }
                });
        });
    </script>
</body>
</html>
<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>About TITAN'S DRIVE</h1>
            <p>Your Premier BMW Rental Experience</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <!-- Our Story -->
            <div class="content-section">
                <div class="content-grid">
                    <div class="content-text">
                        <h2>Our Story</h2>
                        <p>Founded with a passion for luxury and performance, TITAN'S DRIVE has become the leading BMW rental service in India. We believe that everyone deserves to experience the thrill of driving a premium BMW vehicle, whether for a special occasion, business travel, or simply to enjoy the finest in automotive engineering.</p>
                        <p>Since our inception, we've maintained the highest standards of service, ensuring that every customer receives not just a car, but an unforgettable driving experience. Our fleet consists exclusively of the latest BMW models, meticulously maintained to deliver peak performance and comfort.</p>
                    </div>
                    <div class="content-image">
                        <div class="image-placeholder" style="background: url('story.png') no-repeat center center/cover;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission & Vision -->
            <div class="mission-vision-section">
                <div class="mission-vision-grid">
                    <div class="mission-card">
                        <div class="card-icon">🎯</div>
                        <h3>Our Mission</h3>
                        <p>To provide exceptional luxury car rental experiences by offering premium BMW vehicles with outstanding customer service, making luxury accessible to everyone.</p>
                    </div>
                    <div class="mission-card">
                        <div class="card-icon">👁️</div>
                        <h3>Our Vision</h3>2
                        <p>To be recognized as India's most trusted and preferred luxury car rental service, setting the standard for quality, reliability, and customer satisfaction.</p>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="content-section">
                <h2 class="text-center">Why Choose TITAN'S DRIVE?</h2>
                <div class="features-grid-about">
                    <div class="feature-card-about">
                        <div class="feature-icon-large">🚗</div>
                        <h3>Premium Fleet</h3>
                        <p>Exclusive collection of latest BMW models from 3 Series to M5, all meticulously maintained and regularly serviced.</p>
                    </div>
                    <div class="feature-card-about">
                        <div class="feature-icon-large">✓</div>
                        <h3>Easy Booking</h3>
                        <p>Seamless online booking system with instant confirmation and flexible pickup locations across major cities.</p>
                    </div>
                    <div class="feature-card-about">
                        <div class="feature-icon-large">🛡️</div>
                        <h3>Full Insurance</h3>
                        <p>Comprehensive insurance coverage included with every rental for complete peace of mind during your journey.</p>
                    </div>
                    <div class="feature-card-about">
                        <div class="feature-icon-large">💰</div>
                        <h3>Transparent Pricing</h3>
                        <p>No hidden charges. What you see is what you pay. Competitive rates with exceptional value for money.</p>
                    </div>
                    <div class="feature-card-about">
                        <div class="feature-icon-large">👨‍💼</div>
                        <h3>24/7 Support</h3>
                        <p>Round-the-clock customer support ready to assist you with any queries or roadside assistance needs.</p>
                    </div>
                    <div class="feature-card-about">
                        <div class="feature-icon-large">🔧</div>
                        <h3>Regular Maintenance</h3>
                        <p>All vehicles undergo rigorous maintenance checks and professional detailing before every rental.</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="stats-section">
                <h2 class="text-center">Our Achievements</h2>
                <div class="stats-grid-about">
                    <div class="stat-box">
                        <div class="stat-number-large">500+</div>
                        <div class="stat-label-large">Happy Customers</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number-large">15+</div>
                        <div class="stat-label-large">BMW Models</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number-large">5+</div>
                        <div class="stat-label-large">Cities Covered</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number-large">99%</div>
                        <div class="stat-label-large">Satisfaction Rate</div>
                    </div>
                </div>
            </div>

            <!-- Our Values -->
            <div class="content-section">
                <h2 class="text-center">Our Core Values</h2>
                <div class="values-grid">
                    <div class="value-item">
                        <h4>Excellence</h4>
                        <p>We strive for excellence in every aspect of our service, from vehicle quality to customer care.</p>
                    </div>
                    <div class="value-item">
                        <h4>Integrity</h4>
                        <p>We conduct our business with honesty, transparency, and ethical practices at all times.</p>
                    </div>
                    <div class="value-item">
                        <h4>Customer Focus</h4>
                        <p>Our customers are at the heart of everything we do. Your satisfaction is our priority.</p>
                    </div>
                    <div class="value-item">
                        <h4>Innovation</h4>
                        <p>We continuously improve our services and embrace technology to enhance your experience.</p>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="cta-section">
                <h2>Ready to Drive Your Dream BMW?</h2>
                <p>Experience the ultimate luxury and performance with TITAN'S DRIVE</p>
                <div class="cta-buttons">
                    <a href="cars.php" class="btn btn-primary">Browse Our Fleet</a>
                    <a href="contact.php" class="btn btn-secondary">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    
    <style>
        .page-header {
            color: var(--bmw-blue);
            padding: 4rem 0;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            font-size: 1.3rem;
            opacity: 0.95;
        }
        
        .about-content {
            padding: 4rem 0;
        }
        
        .content-section {
            margin-bottom: 4rem;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        
        .content-text h2 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
        }
        
        .content-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-color);
            margin-bottom: 1rem;
        }
        
        .image-placeholder {
            height: 400px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .mission-vision-section {
            background: var(--light-color);
            padding: 3rem;
            border-radius: 15px;
            margin-bottom: 4rem;
        }
        
        .mission-vision-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .mission-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .mission-card h3 {
            color: var(--dark-color);
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        
        .mission-card p {
            color: var(--gray-color);
            line-height: 1.6;
        }
        
        .features-grid-about {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .feature-card-about {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .feature-card-about:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon-large {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-card-about h3 {
            color: var(--dark-color);
            margin-bottom: 1rem;
        }
        
        .feature-card-about p {
            color: var(--gray-color);
            line-height: 1.6;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4rem 2rem;
            border-radius: 15px;
            margin-bottom: 4rem;
            color: #fff;
        }
        
        .stats-section h2 {
            color: #fff;
            margin-bottom: 3rem;
        }
        
        .stats-grid-about {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
        
        .stat-box {
            text-align: center;
        }
        
        .stat-number-large {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .stat-label-large {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .value-item {
            background: var(--light-color);
            padding: 2rem;
            border-radius: 10px;
            border-left: 4px solid var(--bmw-blue);
        }
        
        .value-item h4 {
            color: var(--dark-color);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .value-item p {
            color: var(--gray-color);
            line-height: 1.6;
        }
        
        .cta-section {
            background: var(--light-color);
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            color: var(--gray-color);
            margin-bottom: 2rem;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .mission-vision-grid {
                grid-template-columns: 1fr;
            }
            
            .features-grid-about {
                grid-template-columns: 1fr;
            }
            
            .stats-grid-about {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .values-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>
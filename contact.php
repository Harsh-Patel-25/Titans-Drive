<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Contact Us</h1>
            <p>We're here to help you with any questions</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Information -->
                <div class="contact-info-section">
                    <h2>Get In Touch</h2>
                    <p>Have questions about our services? Need assistance with your booking? We're here to help!</p>
                    
                    <div class="contact-info-cards">
                        <div class="info-card">
                            <div class="info-icon">📍</div>
                            <div class="info-details">
                                <h3>Visit Us</h3>
                                <p>Titan's Drive<br>Anand, Gujarat 388120<br>India</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">📞</div>
                            <div class="info-details">
                                <h3>Call Us</h3>
                                <p>Main: +91 98765 43210<br>Support: +91 98765 43211<br>Mon-Sun: 9:00 AM - 9:00 PM</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">📧</div>
                            <div class="info-details">
                                <h3>Email Us</h3>
                                <p>General: info@titansdrive.com<br>Support: support@titansdrive.com<br>Bookings: bookings@titansdrive.com</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">⏰</div>
                            <div class="info-details">
                                <h3>Business Hours</h3>
                                <p>Monday - Friday: 9:00 AM - 8:00 PM<br>Saturday: 10:00 AM - 6:00 PM<br>Sunday: 10:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Section -->
                    <div class="social-section">
                        <h3>Follow Us</h3>
                        <div class="social-links-large">
                            <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="social-btn"><i class="fa-brands fa-twitter"></i></a>
                            <a href="#" class="social-btn"><i class="fa-brands fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2 class="text-center">Frequently Asked Questions</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h4>What documents do I need to rent a car?</h4>
                        <p>You need a valid driving license, government-issued ID proof (Aadhar/PAN/Passport), and address proof. All documents must be uploaded through our platform for verification.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>What is the minimum rental period?</h4>
                        <p>The minimum rental period is 24 hours (1 day). We offer flexible rental periods from daily to monthly packages.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Is fuel included in the rental price?</h4>
                        <p>No, fuel charges are not included in the rental price. The vehicle will be provided with a full tank and should be returned with a full tank.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Can I cancel or modify my booking?</h4>
                        <p>Yes, you can cancel or modify your booking through your dashboard. Cancellations must be made at least 24 hours before the pickup time for a full refund.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Is insurance included?</h4>
                        <p>Yes, all our rentals include comprehensive insurance coverage. Additional insurance options are available for extended coverage.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>What is your late return policy?</h4>
                        <p>Late returns are charged at ₹500 per hour after the scheduled return time. Please contact us if you need to extend your rental period.</p>
                    </div>
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
        .page-header h1 { font-size: 3rem; margin-bottom: 0.5rem; }
        .page-header p { font-size: 1.3rem; opacity: 0.95; }

        .contact-section { padding: 4rem 0; }

        .contact-info-section h2 { font-size: 2.5rem; color: #111; margin-bottom: 1rem; }
        .contact-info-section > p { color: #555; font-size: 1.1rem; margin-bottom: 2rem; }

        /* Info Cards Grid */
        .contact-info-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-card {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
        .info-icon { font-size: 2.2rem; }
        .info-details h3 { color: #111; margin-bottom: 0.4rem; }
        .info-details p { color: #555; font-size: 0.95rem; line-height: 1.5; margin: 0; }

        /* Social Media */
        .social-section {
            margin-top: 3rem;
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .social-section h3 { color: #111; margin-bottom: 1.5rem; }
        .social-links-large { display: flex; justify-content: center; gap: 1.5rem; }
        .social-btn {
            background: #f4f4f4;
            color: #111;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            background: #667eea;
            color: #fff;
        }

        /* FAQ Section */
        .faq-section { margin: 4rem 0; }
        .faq-section h2 { color: #111; margin-bottom: 3rem; text-align: center; }
        .faq-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; }
        .faq-item { background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); border-left: 4px solid #667eea; }
        .faq-item h4 { color: #111; margin-bottom: 0.8rem; font-size: 1.1rem; }
        .faq-item p { color: #555; line-height: 1.6; margin: 0; }

        /* Responsive */
        @media (max-width: 768px) {
            .contact-info-cards { grid-template-columns: 1fr; }
            .faq-grid { grid-template-columns: 1fr; }
        }
    </style>
</body>
</html>

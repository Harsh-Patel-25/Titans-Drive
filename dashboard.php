<?php
require_once 'config.php';

if(!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->connect();

// Get user bookings
$stmt = $conn->prepare("
    SELECT b.*, c.model_name, c.series, c.image_url 
    FROM bookings b 
    JOIN cars c ON b.car_id = c.car_id 
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();

// Get booking statistics
$stmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
        SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
        SUM(total_price) as total_spent
    FROM bookings 
    WHERE user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch();

// Handle booking cancellation
if(isset($_GET['cancel']) && isset($_GET['booking_id'])) {
    $booking_id = (int)$_GET['booking_id'];
    $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'cancelled' WHERE booking_id = ? AND user_id = ?");
    if($stmt->execute([$booking_id, $_SESSION['user_id']])) {
        showSuccess('Booking cancelled successfully');
        redirect('dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="dashboard">
        <div class="container">
            <h1 class="section-title">My Dashboard</h1>
            
            <?php if($error = getError()): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success = getSuccess()): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <!-- Statistics -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_bookings']; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['confirmed_bookings']; ?></div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['completed_bookings']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">₹<?php echo number_format($stats['total_spent'] ?? 0); ?></div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="cars.php" class="btn btn-primary">Browse Cars</a>
                <a href="profile.php" class="btn btn-secondary">Edit Profile</a>
                <a href="upload_documents.php" class="btn btn-secondary">Upload Documents</a>
            </div>
            
            <!-- Bookings Table -->
            <div class="table-container mt-3">
                <h2>My Bookings</h2>
                
                <?php if(count($bookings) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Car</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Days</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings as $booking): ?>
                                <tr>
                                    <td>#<?php echo $booking['booking_id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($booking['model_name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($booking['series']); ?></small>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($booking['pickup_date'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($booking['return_date'])); ?></td>
                                    <td><?php echo $booking['total_days']; ?></td>
                                    <td>₹<?php echo number_format($booking['total_price']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $booking['booking_status']; ?>">
                                            <?php echo ucfirst($booking['booking_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="booking_details.php?id=<?php echo $booking['booking_id']; ?>" class="btn-small">View</a>
                                        <?php if($booking['payment_status'] == 'pending'): ?>
                                            <a href="payment.php?booking_id=<?php echo $booking['booking_id']; ?>" 
                                               class="btn-small" 
                                               style="background: var(--success-color);">Pay Now</a>
                                        <?php endif; ?>
                                        <?php if($booking['booking_status'] == 'pending' || $booking['booking_status'] == 'confirmed'): ?>
                                            <a href="dashboard.php?cancel=1&booking_id=<?php echo $booking['booking_id']; ?>" 
                                               class="btn-small btn-danger" 
                                               onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>You haven't made any bookings yet.</p>
                        <a href="cars.php" class="btn btn-primary">Browse Our Fleet</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    
    <style>
        .quick-actions {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .btn-small {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: var(--bmw-blue);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            margin: 0 0.2rem;
        }
        
        .btn-danger {
            background: var(--danger-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
        }
        
        .empty-state p {
            font-size: 1.2rem;
            color: var(--gray-color);
            margin-bottom: 1.5rem;
        }
        
    </style>
</body>
</html>
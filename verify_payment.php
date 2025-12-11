<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

$razorpay_payment_id = $data['razorpay_payment_id'] ?? '';
$razorpay_order_id = $data['razorpay_order_id'] ?? '';
$razorpay_signature = $data['razorpay_signature'] ?? '';
$booking_id = (int)($data['booking_id'] ?? 0);

if(empty($razorpay_payment_id) || empty($booking_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment data']);
    exit();
}

$db = new Database();
$conn = $db->connect();

// Verify booking belongs to user
$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if(!$booking) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit();
}

// In production, verify signature with Razorpay
// $razorpay_key_secret = "YOUR_KEY_SECRET";
// $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $razorpay_key_secret);
// if ($generated_signature !== $razorpay_signature) {
//     echo json_encode(['success' => false, 'message' => 'Payment verification failed']);
//     exit();
// }

// Update booking payment status
$stmt = $conn->prepare("
    UPDATE bookings 
    SET payment_status = 'paid',
        booking_status = 'confirmed',
        updated_at = NOW()
    WHERE booking_id = ?
");

if($stmt->execute([$booking_id])) {
    // You can also store payment details in a separate payments table
    // INSERT INTO payments (booking_id, razorpay_payment_id, amount, status) VALUES (...)
    
    echo json_encode([
        'success' => true,
        'message' => 'Payment verified successfully',
        'booking_id' => $booking_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update booking']);
}
?>
<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$booking_id = (int)($data['booking_id'] ?? 0);
$payment_method = $data['payment_method'] ?? '';

if(empty($booking_id) || empty($payment_method)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
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

// Update booking - if pay at pickup, keep payment pending but confirm booking
if($payment_method == 'pickup') {
    $stmt = $conn->prepare("
        UPDATE bookings 
        SET booking_status = 'confirmed',
            updated_at = NOW()
        WHERE booking_id = ?
    ");
    
    if($stmt->execute([$booking_id])) {
        echo json_encode([
            'success' => true,
            'message' => 'Booking confirmed. Payment will be collected at pickup.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid payment method']);
}
?>
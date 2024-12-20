<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /view/manager/manage.php");
    exit();
}

// Verify we have the necessary session data
if (!isset($_SESSION['ride_id']) || !isset($_SESSION['payment_status'])) {
    header("Location: booking.php");
    exit();
}

// Get data from session
$ride_id = $_SESSION['ride_id'];
$payment_status = $_SESSION['payment_status'];
$user_id = $_SESSION['user_id'];
$plate_number = $_SESSION['plate_number'];
$departure = $_SESSION['departure'];
$capacity = $_SESSION['capacity'];
$route = $_SESSION['route'];
$total_fare = $_SESSION['total_fare'] ?? 15;
$payment_method = 'Gcash';
$status = 'Active';

// Insert reservation into the database
$reservation_time = date('Y-m-d H:i:s');

$query = "
    INSERT INTO reservations (user_id, ride_id, reservation_time, status, payment_status, total_fare, payment_method)
    VALUES (?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("iisssis", $user_id, $ride_id, $reservation_time, $status, $payment_status, $total_fare, $payment_method);

if ($stmt->execute()) {
    // Update available seats in rides table
    $updateQuery = "UPDATE rides SET seats_available = seats_available - 1 WHERE ride_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $ride_id);
    $updateStmt->execute();
    
    // Fetch queue position
    $query = "SELECT COUNT(*) AS queue FROM reservations WHERE ride_id = ? AND status = 'Active'";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $ride_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $queue = $row['queue'] ?? 1;
    } else {
        $queue = 'Unknown';
    }
} else {
    header("Location: booking.php");
    exit();
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png"> 
    <link rel="stylesheet" href="styles.css">
</head>
<body id="confirm-bod">
    <!-- Container for the booking details of the passenger -->
    <div class="confirm-cont">
        <h1>Booking Confirmed!</h1>
        <img src="/view/assets/confirm.png" alt="Confirmation">
        <span class="label">Thank you for booking! Your queue number is:</span> <span class="value"><?= htmlspecialchars($queue); ?></span>
        <p>Please be ready at your pickup location. You are currently no. <span class="value"><?= htmlspecialchars($queue); ?></span> in queue.</p>

        <div class="ride-details">
            <h1>Ride Details</h1>
            <div class="detail-item">
                <span class="label">Plate No.:</span> <span class="value"><?= htmlspecialchars($plate_number); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Departure:</span> <span class="value"><?= date('g:i A', strtotime($departure)); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Capacity:</span> <span class="value"><?= htmlspecialchars($capacity); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Status:</span> <span class="value"><?= htmlspecialchars($status); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Route:</span> <span class="value"><?= htmlspecialchars($route); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Total Fare:</span> <span class="value">₱<?= htmlspecialchars($total_fare); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Payment Method:</span> <span class="value"><?= htmlspecialchars($payment_method); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Payment Status:</span> <span class="value"><?= htmlspecialchars($payment_status); ?></span>
            </div>
        </div>
        <button type="button" class="confirm-btn" onclick="window.location.href='booking.php'">Return to Home</button>
    </div>
</body>
</html>
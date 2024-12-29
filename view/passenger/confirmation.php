<?php
session_start();
if (!isset($_SESSION['queue'])) {
    header("Location: booking.php");
    exit();
}

// Retrieve session data
$queue = $_SESSION['queue'];
$plate_number = $_SESSION['plate_number'];
$departure = $_SESSION['departure'];
$capacity = $_SESSION['capacity'];
$route = $_SESSION['route'];
$total_fare = $_SESSION['total_fare'];
$payment_method = $_SESSION['payment_method']; // Dynamically retrieve payment method
$payment_status = $_SESSION['payment_status'];
$status = 'Active';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="confirm-cont">
        <h1>Booking Confirmed!</h1>
        <p>Your queue number is: <strong><?= htmlspecialchars($queue); ?></strong></p>
        <p>Be ready at your pickup location.</p>

        <div class="ride-details">
            <h2>Ride Details</h2>
            <div class="detail-item">
                <span class="label">Plate No.:</span> <span class="value"><?= htmlspecialchars($plate_number); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Departure:</span> <span class="value"><?= htmlspecialchars(date('g:i A', strtotime($departure))); ?></span>
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
        <button onclick="window.location.href='booking.php'">Return to Home</button>
    </div>
</body>
</html>

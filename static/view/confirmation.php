<?php
session_start(); // Start session to access logged-in user data
require_once('../control/includes/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect ride_id and user_id from session
    $ride_id = $_POST['ride_id'] ?? NULL;
    $user_id = $_SESSION['user_id'] ?? NULL; // User ID from session

    if (!$ride_id || !$user_id) {
        echo "Invalid ride selection or user not logged in.";
        exit();
    }

    // Set default values
    $reservation_time = date('Y-m-d H:i:s');
    $status = 'Active';
    $payment_status = 'Not Paid';
    $total_fare = 13;
    $payment_method = 'Cash';

    // Insert reservation into database
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
        header("Location: gcash.php");
        exit();
    } else {
        echo "Error: Could not store reservation. " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
    exit();
}
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
    <div class="confirm-cont">
        <h1>Booking Confirmed!</h1>
        <img src="../assets/confirm.png" alt="Confirmation">
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
                <span class="label">Payment Method: Gcash</span> <span class="value"><?= htmlspecialchars($payment_method); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Payment Status: Paid</span> <span class="value"><?= htmlspecialchars($payment_status); ?></span>
            </div>
        </div>
        <button type="button" class="confirm-btn" onclick="window.location.href='booking.php'">Return to Home</button>
    </div>
</body>
</html>

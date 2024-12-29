<?php
require_once('../../control/includes/db.php');
session_start();

// Ensure the ride ID is set and valid
if (isset($_POST['ride_id']) && is_numeric($_POST['ride_id'])) {
    $ride_id = (int)$_POST['ride_id'];

    $query = "SELECT r.*, 
                     res.total_fare, 
                     res.payment_method, 
                     res.payment_status, 
                     res.status 
              FROM rides r 
              LEFT JOIN reservations res 
              ON r.ride_id = res.ride_id 
              WHERE r.ride_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("SQL prepare failed: " . $conn->error);
        echo "Sorry, something went wrong. Please try again later.";
        exit();
    }

    $stmt->bind_param('i', $ride_id);
    $stmt->execute();
    $ride_details = $stmt->get_result()->fetch_assoc();

    if ($ride_details) {
        // Set session variables for booking confirmation
        $_SESSION['ride_id'] = $ride_id;
        $_SESSION['plate_number'] = $ride_details['plate_number'];
        $_SESSION['departure'] = $ride_details['departure'];
        $_SESSION['capacity'] = $ride_details['capacity'];
        $_SESSION['route'] = $ride_details['route'];
        $_SESSION['total_fare'] = $ride_details['total_fare'] ?? 15;
        $_SESSION['payment_status'] = $ride_details['payment_status'] ?? 'Pending';
    } else {
        echo "No ride found with the selected ID.";
        exit();
    }
} else {
    echo "Invalid or missing ride ID.";
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
    <title>Review Ride Details</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
<div class="details-cont">
    <h1>Review Your Ride Details</h1>
    <div class="ride-details">
        <div class="detail-item">
            <span class="label">Route:</span> <span class="value"><?= htmlspecialchars($_SESSION['route']); ?></span>
        </div>
        <div class="detail-item">
            <span class="label">Plate No.:</span> <span class="value"><?= htmlspecialchars($_SESSION['plate_number']); ?></span>
        </div>
        <div class="detail-item">
            <span class="label">Departure:</span> <span class="value"><?= htmlspecialchars(date('g:i A', strtotime($_SESSION['departure']))); ?></span>
        </div>
        <div class="detail-item">
            <span class="label">Capacity:</span> <span class="value"><?= htmlspecialchars($_SESSION['capacity']); ?></span>
        </div>
        <div class="detail-item">
            <span class="label">Total Fare:</span> <span class="value">₱<?= htmlspecialchars($_SESSION['total_fare']); ?></span>
        </div>
    </div>
    <!-- Payment Method Section -->
    <form action="confirm_booking.php" method="POST">
        <input type="hidden" name="ride_id" value="<?= htmlspecialchars($_SESSION['ride_id']); ?>">
        <div class="payment-method-container">
            <h2 class="payment-method-title">Select Payment Method</h2>
            <select name="payment_method" id="payment_method" class="payment-method-select" required>
                <option value="GCash">GCash</option>
                <option value="Manual">Manual</option>
            </select>
        </div>
        <button type="submit" class="confirm-btn">Confirm Booking</button>
    </form>
    <button type="button" class="cancel-btn" onclick="window.location.href='booking.php'">Cancel</button>
</div>

</body>
</html>

<!-- Frontend by: Jemma Niduaza
     Backend by: Jemma Niduaza -->
<?php
session_start(); // Start session to access data
require_once $_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Retrieve data from session
    $ride_id = $_SESSION['ride_id'] ?? null;
    $payment_status = $_SESSION['payment_status'] ?? 'Pending';
    $user_id = $_SESSION['user_id'] ?? null; // User ID from session
    $plate_number = $_POST['plate_number'] ?? null;
    $departure = $_POST['departure'] ?? null;
    $capacity = $_POST['capacity'] ?? null;
    $route = $_POST['route'] ?? null;

    if (!$ride_id) {
        echo "Invalid ride.";
        exit();
    }

    if ( !$user_id) {
        echo "user not logged in.";
        exit();
    }

    // Insert reservation into the database
    $reservation_time = date('Y-m-d H:i:s');
    $status = 'Active';
    $total_fare = 13; // Replace with dynamic value if needed
    $payment_method = 'Gcash';

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
        // Fetch queue position
        $query = "SELECT COUNT(*) AS queue FROM reservations WHERE ride_id = ? AND status = 'Active'";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $ride_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $queue = $row['queue'] ?? 1; // Default to 1 if no queue data is found
        } else {
            $queue = 'Unknown'; // Fallback if query fails
        }
    
        unset($_SESSION['ride_id'], $_SESSION['payment_status']); // Clear session data
    } else {
        echo "Error: Could not store reservation. " . $stmt->error;
        exit();
    }
    
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
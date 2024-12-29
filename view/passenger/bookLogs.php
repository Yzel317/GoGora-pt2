<?php
session_start();
require_once '../../control/includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /view/manager/manage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch active reservations for the user along with queue position
$query = "
    SELECT r.*, ri.route, ri.plate_number, ri.departure,
    (SELECT COUNT(*) 
     FROM reservations r2 
     WHERE r2.ride_id = r.ride_id 
       AND r2.status = 'Active' 
       AND r2.reservation_time <= r.reservation_time) AS queue_number
    FROM reservations r
    JOIN rides ri ON r.ride_id = ri.ride_id
    WHERE r.user_id = ? AND r.status = 'Active'
    ORDER BY r.reservation_time DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$active_bookings = [];

while ($row = $result->fetch_assoc()) {
    $active_bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Booking Logs</title>
    <link rel="icon" type="image/png" href="/view/assets/favicon.png">
    <link rel="stylesheet" href="booklog.css"> <!-- Link the new CSS -->
</head>
<body id="book-bod">


<div class="book-cont">
    <button onclick="window.location.href='booking.php';" class="back-button">Back</button> <!-- Back button -->
    <h1>Booking history</h1>
    <section class="ride-list" id="rideListContainer">
        <?php if (empty($active_bookings)): ?>
            <p>No active bookings found.</p>
        <?php else: ?>
            <?php foreach ($active_bookings as $booking): ?>
                <div class="ride-item active">
                    <div class="ride-info">
                        <p>Route: <?= htmlspecialchars($booking['route']) ?></p>
                        <p>Date: <?= date('F j, Y', strtotime($booking['reservation_time'])) ?></p>
                        <p>Time: <?= date('g:i A', strtotime($booking['departure'])) ?></p>
                        <p>Plate No: <?= htmlspecialchars($booking['plate_number']) ?></p>
                        <p>Status: <?= htmlspecialchars($booking['status']) ?></p>
                        <p>Payment: ₱<?= htmlspecialchars($booking['total_fare']) ?> 
                           (<?= htmlspecialchars($booking['payment_status']) ?>)</p>
                        <p>Queue Position: <?= htmlspecialchars($booking['queue_number'] ?? 'N/A') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>

</body>
</html>

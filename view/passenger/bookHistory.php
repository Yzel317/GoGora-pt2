<?php
session_start();
require_once('../../control/includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /view/manager/manage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch booking history for the logged-in user
$query = "
    SELECT res.reservation_id, r.route, r.departure, r.plate_number, res.total_fare, res.status, res.reservation_time, res.payment_status, res.ride_id
    FROM reservations res
    INNER JOIN rides r ON res.ride_id = r.ride_id
    WHERE res.user_id = ?
    ORDER BY res.reservation_time DESC
";
$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("SQL prepare failed: " . $conn->error);
    die("Sorry, something went wrong. Please try again later.");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);

// Calculate queue position for each booking
$queueQuery = "
    SELECT COUNT(*) AS queue
    FROM reservations
    WHERE ride_id = ? AND status = 'Active' AND reservation_time <= ?
";

foreach ($bookings as &$booking) {
    $stmt = $conn->prepare($queueQuery);
    if ($stmt) {
        $stmt->bind_param("is", $booking['ride_id'], $booking['reservation_time']);
        $stmt->execute();
        $queueResult = $stmt->get_result();
        $queueData = $queueResult->fetch_assoc();
        $booking['queue_position'] = $queueData['queue'] ?? 'N/A';
    } else {
        $booking['queue_position'] = 'N/A';
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
    <title>GoGora - Booking History</title>
    <link rel="icon" type="image/png" href="/view/assets/favicon.png">
    <link rel="stylesheet" href="booklog.css">
</head>
<body id="book-bod">


<div class="book-cont">
    <button class="back-button" onclick="window.location.href='booking.php';">Back</button>
    <h1>Bookings log</h1>
    <section class="ride-list" id="rideListContainer">
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="ride-item">
                    <span class="status"><?= htmlspecialchars($booking['status']); ?></span>
                    <div class="ride-info">
                        <p><strong>Route:</strong> <?= htmlspecialchars($booking['route']); ?></p>
                        <p><strong>Date:</strong> <?= htmlspecialchars(date('F j, Y', strtotime($booking['reservation_time']))); ?></p>
                        <p><strong>Time:</strong> <?= htmlspecialchars(date('g:i A', strtotime($booking['reservation_time']))); ?></p>
                        <p><strong>Plate No.:</strong> <?= htmlspecialchars($booking['plate_number']); ?></p>
                        <p class="payment"><strong>Payment:</strong> ₱<?= htmlspecialchars($booking['total_fare']) . " (" . htmlspecialchars($booking['payment_status']) . ")"; ?></p>
                        <p><strong>Queue Position:</strong> <?= htmlspecialchars($booking['queue_position']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Booking History</p>
        <?php endif; ?>
    </section>
</div>

</body>
</html>

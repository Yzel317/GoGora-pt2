<!-- Authors: Jemma Niduaza, Mark Jervin Galarce -->
<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /view/manager/manage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch active reservations for the user
$query = "
    SELECT r.*, ri.route, ri.plate_number, ri.departure
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
    <link rel="stylesheet" href="style2.css">
</head>
<body id="book-bod">
    <header class="book-head">
        <h1>GoGora</h1>
        <a href="#"><img src="/view/assets/profile.png" alt="Profile"></a>
        <div class="logout-link">
            <a href="/view/manager/manage.php"><img src="/view/assets/logout.png"></a>
        </div>
    </header>

    <div class="book-cont">
        <form id="filterForm">
            <label for="route">Search Route:</label>
            <input type="text" name="route" id="route" placeholder="Enter route name">

            <label for="ride_type">Select Ride Type:</label>
            <select name="ride_type" id="ride_type">
                <option value="All">All types</option>
                <option value="Jeepney">Jeepney</option>
                <option value="Service">Service</option>
            </select>

            <label for="time">Select Time:</label>
            <select name="time" id="time">
                <option value="All">All Times</option>
                <?php
                    for ($hour = 7; $hour <= 19; $hour++) {
                        $time_option = sprintf('%02d:00:00', $hour);
                        $display_time = date('g:i A', strtotime($time_option));
                        echo "<option value='$time_option'>$display_time</option>";
                    }
                ?>
            </select>

            <button type="submit">Search</button>
            <button type="button" onclick="window.location.reload();">Clear Filter</button>
            <button type="button" onclick="window.history.back();">Back</button>
        </form>

        <h1>Active Bookings</h1>
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

    <style>
        .ride-item {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .ride-item.active {
            border-left: 4px solid #007bff;
        }

        .ride-info p {
            margin: 5px 0;
            color: #333;
        }
    </style>
</body>
</html>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php');// Database connection file

// Fetch Reservations
$reservations = [];
$reservation_query = "
    SELECT res.reservation_id, r.route, r.departure AS schedule, res.status, res.payment_status 
    FROM reservations AS res
    JOIN rides AS r ON res.ride_id = r.ride_id
";
$reservation_result = mysqli_query($conn, $reservation_query);
if ($reservation_result) {
    $reservations = mysqli_fetch_all($reservation_result, MYSQLI_ASSOC);
}

// Fetch Available Rides
$available_rides = [];
$rides_query = "
    SELECT ride_id, route, departure AS schedule, seats_available 
    FROM rides
";
$rides_result = mysqli_query($conn, $rides_query);
if ($rides_result) {
    $available_rides = mysqli_fetch_all($rides_result, MYSQLI_ASSOC);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Manage Reservations</title>
    <link rel="stylesheet" href="../manager/css/styles.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <div class="logo">
                <h1>GoGora</h1>
            </div>
            <div class="nav-title">MANAGER</div>
            <ul>
                <li><a href="../manager/dashboard.php"><span class="icon">📊</span> Dashboard</a></li>
                <li><a href="../manager/ride.php"><span class="icon">🚗</span> Ride Management</a></li>
                <li><a href="../manager/route.php"><span class="icon">🛣️</span> Route Management</a></li>
                <li><a href="../manager/account.php"><span class="icon">👤</span> Account Management</a></li>
                <li><a href="../manager/priority.php"><span class="icon">⭐</span> Priority Lane Management</a></li>
                <li><a href="../manager/reservations.php"><span class="icon">📝</span> Reservations</a></li>
            </ul>
            <div class="logout">
                <a href="#"><span class="icon">🚪</span> Logout</a>
            </div>
        </nav>
        <main class="content">
            <header>
                <h2>Reservations</h2>
                <a href="../manager/dashboard.php" class="back-link">Back to Dashboard</a>
            </header>

            <!-- Reservations Table -->
            <section class="accounts">
                <div class="section-header">
                    <h3>Reservations</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Route</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= htmlspecialchars($reservation['reservation_id']); ?></td>
                                <td><?= htmlspecialchars($reservation['route']); ?></td>
                                <td><?= htmlspecialchars($reservation['schedule']); ?></td>
                                <td><?= htmlspecialchars($reservation['status']); ?></td>
                                <td><?= htmlspecialchars($reservation['payment_status']); ?></td>
                                <td>
                                    <button class="action-btn edit">✏️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="6">No reservations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <!-- Available Rides Table -->
            <section class="blacklisted">
                <div class="section-header">
                    <h3>Available Rides</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Ride ID</th>
                            <th>Route</th>
                            <th>Schedule</th>
                            <th>Seats Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($available_rides as $ride): ?>
                            <tr>
                                <td><?= htmlspecialchars($ride['ride_id']); ?></td>
                                <td><?= htmlspecialchars($ride['route']); ?></td>
                                <td><?= htmlspecialchars($ride['schedule']); ?></td>
                                <td><?= htmlspecialchars($ride['seats_available']); ?></td>
                                <td>
                                    <button class="action-btn edit">✏️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($available_rides)): ?>
                            <tr><td colspan="5">No available rides found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

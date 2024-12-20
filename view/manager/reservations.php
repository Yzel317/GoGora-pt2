<?php
/* Authors: Jemma Niduaza, Mark Jervin Galarce */
require_once $_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php';

// Fetch reservations with user and ride details
$query = "SELECT r.reservation_id, r.status, r.payment_status, 
                 rides.route, rides.departure, rides.seats_available,
                 rides.ride_id, rides.plate_number
          FROM reservations r
          JOIN rides ON r.ride_id = rides.ride_id
          ORDER BY r.reservation_time DESC";

$result = $conn->query($query);
$reservations = $result->fetch_all(MYSQLI_ASSOC);

// Fetch available rides
$rides_query = "SELECT * FROM rides WHERE seats_available > 0 ORDER BY departure";
$rides_result = $conn->query($rides_query);
$available_rides = $rides_result->fetch_all(MYSQLI_ASSOC);
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
                <a href="/view/manager/manage.php"><span class="icon">🚪</span> Logout</a>
            </div>
        </nav>
        <main class="content">
            <header>
                <h2>Reservations</h2>
                <a href="../manager/dashboard.php" class="back-link">Back to Dashboard</a>
            </header>
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
                                <td><?= htmlspecialchars($reservation['reservation_id']) ?></td>
                                <td><?= htmlspecialchars($reservation['route']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($reservation['departure'])) ?></td>
                                <td><?= htmlspecialchars($reservation['status']) ?></td>
                                <td><?= htmlspecialchars($reservation['payment_status']) ?></td>
                                <td>
                                    <button class="action-btn edit" 
                                            onclick="editReservation(<?= $reservation['reservation_id'] ?>)">✏️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
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
                                <td><?= htmlspecialchars($ride['ride_id']) ?></td>
                                <td><?= htmlspecialchars($ride['route']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($ride['departure'])) ?></td>
                                <td><?= htmlspecialchars($ride['seats_available']) ?></td>
                                <td>
                                    <button class="action-btn edit" 
                                            onclick="editRide(<?= $ride['ride_id'] ?>)">✏️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        function editReservation(id) {
            // Add your edit reservation logic here
            console.log('Editing reservation:', id);
        }

        function editRide(id) {
            // Add your edit ride logic here
            console.log('Editing ride:', id);
        }
    </script>
</body>
</html>
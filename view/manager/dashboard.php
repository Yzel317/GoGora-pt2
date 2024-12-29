<?php
/* Authors: Jemma Niduaza, Mark Jervin Galarce */
include('../../control/includes/db.php');


// Get total registered users
$usersQuery = "SELECT COUNT(*) as total_users FROM users WHERE role != 'Admin' AND role != 'Manager'";
$usersResult = $conn->query($usersQuery);
$totalUsers = $usersResult->fetch_assoc()['total_users'];

// Get total bookings
$bookingsQuery = "SELECT COUNT(*) as total_bookings FROM reservations";
$bookingsResult = $conn->query($bookingsQuery);
$totalBookings = $bookingsResult->fetch_assoc()['total_bookings'];

// Get total active passengers (users with active reservations)
$activePassengersQuery = "SELECT COUNT(DISTINCT user_id) as total_passengers 
                         FROM reservations 
                         WHERE status = 'Active'";
$passengersResult = $conn->query($activePassengersQuery);
$totalPassengers = $passengersResult->fetch_assoc()['total_passengers'];

// Get total blacklisted passengers
$blacklistQuery = "SELECT COUNT(*) as total_blacklisted FROM blacklist";
$blacklistResult = $conn->query($blacklistQuery);
$totalBlacklisted = $blacklistResult->fetch_assoc()['total_blacklisted'];

// Get recent bookings
$recentBookingsQuery = "SELECT r.reservation_id, u.firstname, u.lastname, 
                              rides.route, r.reservation_time, r.status
                       FROM reservations r
                       JOIN users u ON r.user_id = u.user_id
                       JOIN rides ON r.ride_id = rides.ride_id
                       ORDER BY r.reservation_time DESC
                       LIMIT 5";
$recentBookings = $conn->query($recentBookingsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora Manager Dashboard</title>
    <link rel="stylesheet" href="../manager/css/styles.css">
    <style>
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin: 20px;
        }

        .dashboard-stat {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dashboard-stat h3 {
            margin: 0;
            color: #333;
            font-size: 1.1em;
        }

        .dashboard-stat p {
            margin: 10px 0 0;
            font-size: 2em;
            font-weight: bold;
            color: #0066cc;
        }

        .recent-bookings {
            margin: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .recent-bookings h3 {
            margin-top: 0;
            color: #333;
        }

        .recent-bookings table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .recent-bookings th, 
        .recent-bookings td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .recent-bookings th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
        }

        .status-active {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
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
                <a href="/GoGora-pt2/view/manager/manage.php"><span class="icon">🚪</span> Logout</a>
            </div>
        </div>
        <div class="content">
            <header>
                <h1>Dashboard</h1>
            </header>
            <div class="dashboard-stats">
                <div class="dashboard-stat">
                    <h3>Total Registered Users</h3>
                    <p><?= $totalUsers ?></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Total Bookings</h3>
                    <p><?= $totalBookings ?></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Active Passengers</h3>
                    <p><?= $totalPassengers ?></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Blacklisted Users</h3>
                    <p><?= $totalBlacklisted ?></p>
                </div>
            </div>

            <div class="recent-bookings">
                <h3>Recent Bookings</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Passenger</th>
                            <th>Route</th>
                            <th>Booking Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $recentBookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['firstname'] . ' ' . $booking['lastname']) ?></td>
                                <td><?= htmlspecialchars($booking['route']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($booking['reservation_time'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                        <?= htmlspecialchars($booking['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
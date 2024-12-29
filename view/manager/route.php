<!-- <?php
include('../../control/includes/db.php');
// Include database connection

// Handle delete request for rides
if (isset($_GET['delete_ride'])) {
    $ride_id = $_GET['delete_ride'];
    $deleteRideQuery = "DELETE FROM rides WHERE ride_id = ?";
    $stmt = $conn->prepare($deleteRideQuery);
    $stmt->bind_param("i", $ride_id);

    if ($stmt->execute()) {
        echo "<script>alert('Ride deleted successfully!'); window.location.href = 'route.php';</script>";
    } else {
        echo "<script>alert('Error deleting ride: " . $conn->error . "'); window.location.href = 'route.php';</script>";
    }
    $stmt->close();
}

// Fetch all rides
$ridesQuery = "SELECT ride_id, plate_number, ride_type, route, time, seats_available, departure FROM rides";
$ridesResult = mysqli_query($conn, $ridesQuery);
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Manage Routes</title>
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
            
        </nav>
        <main class="content">
            <header>
                <h2>Manage Routes</h2>
                <a href="../manager/dashboard.php" class="back-link">Back to Dashboard</a>
            </header>
            <section class="accounts">
                <div class="section-header">
                    <h3>Routes</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Plate No.</th>
                            <th>Type</th>
                            <th>Route</th>
                            <th>Schedule</th>
                            <th>Seats</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ride = mysqli_fetch_assoc($ridesResult)): ?>
                            <tr>
                                <td><?= htmlspecialchars($ride['plate_number']) ?></td>
                                <td><?= htmlspecialchars($ride['ride_type']) ?></td>
                                <td><?= htmlspecialchars($ride['route']) ?></td>
                                <td><?= htmlspecialchars(date('g:i A', strtotime($ride['time'])) . ' to ' . date('g:i A', strtotime($ride['departure']))) ?></td>
                                <td><?= htmlspecialchars($ride['seats_available']) ?></td>
                                <td>
                                    <button class="action-btn edit"><a href="updateRoute.php?ride_id=<?= $ride['ride_id'] ?>">✏️</a></button>
                                    <button class="action-btn delete" onclick="deleteRide(<?= $ride['ride_id'] ?>)">🗑️</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>
        function deleteRide(ride_id) {
            if (confirm('Are you sure you want to delete this ride?')) {
                window.location.href = `route.php?delete_ride=${ride_id}`;
            }
        }
    </script>
</body>
</html>

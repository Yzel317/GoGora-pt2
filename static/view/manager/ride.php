<?php
     include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php');  // Include database connection

   // Fetch rides from the database
   $sql = "SELECT plate_number, ride_type, route, CONCAT(DATE_FORMAT(time, '%h:%i %p'), ' to ', DATE_FORMAT(departure, '%h:%i %p')) as schedule, seats_available FROM rides";
   $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Manage Rides</title>
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
                <h2>Manage Rides</h2>
                <a href="../manager/dashboard.php" class="back-link">Back to Dashboard</a>
            </header>
            <section class="accounts">
                <div class="section-header">
                    <h3>Accounts</h3>
                    <button class="add-user"><a href="../manager/addRide.php">Add Ride</a></button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Ride</th>
                            <th>Type</th>
                            <th>Route</th>
                            <th>Schedule</th>
                            <th>Seats</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            // Output data for each ride
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>{$row['plate_number']}</td>
                                        <td>{$row['ride_type']}</td>
                                        <td>{$row['route']}</td>
                                        <td>{$row['schedule']}</td>
                                        <td>{$row['seats_available']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No rides available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

<?php
   // Include the necessary files for database connection
   include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php'); 
   
   // Query to get the total registered users
   $total_users_query = "SELECT COUNT(*) AS total_users FROM users";
   $total_users_result = mysqli_query($conn, $total_users_query);
   $total_users = mysqli_fetch_assoc($total_users_result)['total_users'];

   // Query to get the total bookings
   $total_bookings_query = "SELECT COUNT(*) AS total_bookings FROM reservations";
   $total_bookings_result = mysqli_query($conn, $total_bookings_query);
   $total_bookings = mysqli_fetch_assoc($total_bookings_result)['total_bookings'];

   // Query to get the total available rides
   $total_available_rides_query = "SELECT COUNT(*) AS total_available_rides FROM rides";
   $total_available_rides_result = mysqli_query($conn, $total_available_rides_query);
   $total_available_rides = mysqli_fetch_assoc($total_available_rides_result)['total_available_rides'];

   // Query to get the total blacklisted passengers
   $blacklisted_passengers_query = "SELECT COUNT(*) AS blacklisted_passengers FROM blacklist WHERE blacklist_status = 'Blacklisted'";
   $blacklisted_passengers_result = mysqli_query($conn, $blacklisted_passengers_query);
   $blacklisted_passengers = mysqli_fetch_assoc($blacklisted_passengers_result)['blacklisted_passengers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora Manager Dashboard</title>
    <link rel="stylesheet" href="../manager/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <h1>GoGora</h1>
            </div>
            <div class="nav-title">Manager</div>
            <ul>
                <li><a href="../manager/dashboard.php"><span class="icon">📊</span> Dashboard</a></li>
                <li><a href="../manager/ride.php"><span class="icon">🚗</span> Ride Management</a></li>
                <li><a href="../manager/route.php"><span class="icon">🛣️</span> Route Management</a></li>
                <li><a href="../manager/account.php"><span class="icon">👤</span> Account Management</a></li>
                <li><a href="../manager/priority.php"><span class="icon">⭐</span> Priority Lane Management</a></li>
                <li><a href="../manager/reservations.php"><span class="icon">📝</span> Reservations</a></li>
            </ul>
            <div class="logout">
                <a href="#">Logout</a>
            </div>
        </div>
        <div class="content">
            <header>
                <h1>Dashboard</h1>
            </header>
            <div class="dashboard-stats">
                <div class="dashboard-stat">
                    <h3>Total Registered Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Total Bookings</h3>
                    <p><?php echo $total_bookings; ?></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Total Available Rides</h3>
                    <p><?php echo $total_available_rides; ?></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Blacklisted Passengers</h3>
                    <p><?php echo $blacklisted_passengers; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!-- <?php
   include ('../GoGora-main/control/includes.php');
  
   ?> -->
   
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
                    <p></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Total Bookings</h3>
                    <p></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Total Passengers</h3>
                    <p></p>
                </div>
                <div class="dashboard-stat">
                    <h3>Blacklisted Passengers</h3>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
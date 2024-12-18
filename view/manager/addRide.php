<?php
   include($_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php');  // Include database connection

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Retrieve form data
       $plate_number = $_POST['plate_number'];
       $route = $_POST['destination_from'] . ' to ' . $_POST['destination_to'];
       $time = $_POST['pickup_time'];
       $departure = $_POST['departure_time'];
       $seats_available = $_POST['seating_capacity'];
       $ride_type = $_POST['ride_type'];
       $capacity = $_POST['seating_capacity']; // Assuming capacity equals seating capacity
       $queue = 0; // Default queue is 0

       // Prepare and execute the SQL query
       $sql = "INSERT INTO rides (plate_number, route, time, departure, seats_available, ride_type, capacity, queue) 
               VALUES ('$plate_number', '$route', '$time', '$departure', '$seats_available', '$ride_type', '$capacity', '$queue')";

       if (mysqli_query($conn, $sql)) {
           echo "<script>alert('New ride added successfully!');</script>";
       } else {
           echo "<script>alert('Error: Could not add ride.');</script>";
       }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Add Ride</title>
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

        <!-- Main Content -->
        <div class="main-content">
            <form class="update-form" method="POST">
                <h2 class="form-title">ADD A RIDE</h2>
                
                <div class="form-group">
                    <label for="plate_number">Plate Number</label>
                    <input type="text" id="plate_number" name="plate_number" maxlength="6" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ride_type">Type of Ride</label>
                        <select id="ride_type" name="ride_type" required>
                            <option value="">Select Type</option>
                            <option value="Jeepney">Jeepney</option>
                            <option value="Service">Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="seating_capacity">Seating Capacity</label>
                        <input type="number" id="seating_capacity" name="seating_capacity" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="destination_to">Going To:</label>
                        <input type="text" id="destination_to" name="destination_to" maxlength="45" required>
                    </div>
                    <div class="form-group">
                        <label for="destination_from">From:</label>
                        <input type="text" id="destination_from" name="destination_from" maxlength="45" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pickup_time">Pickup Time</label>
                        <input type="datetime-local" id="pickup_time" name="pickup_time" required>
                    </div>
                    <div class="form-group">
                        <label for="departure_time">Departure Time</label>
                        <input type="datetime-local" id="departure_time" name="departure_time" required>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-add">ADD</button>
                    <button type="button" class="btn btn-back" onclick="history.back()">BACK</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

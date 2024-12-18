<?php
include($_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php');  // Include database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch the ride data to update
if (isset($_GET['ride_id'])) {
    $ride_id = $_GET['ride_id'];
    $query = "SELECT * FROM rides WHERE ride_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ride_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ride = $result->fetch_assoc();
    if (!$ride) {
        echo "<script>alert('Ride not found!'); window.location.href = 'route.php';</script>";
        exit;
    }
    $stmt->close();
}

// Handle form submission to update ride data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $plate_number = $_POST['place_number'];
    $ride_type = $_POST['bus_type'];
    $seating_capacity = intval($_POST['seating_capacity']); // Convert to integer to ensure proper handling
    $destination_to = $_POST['destination_to'];
    $destination_from = $_POST['destination_from'];
    $schedule_time = $_POST['schedule_time'];
    $arrival_time = $_POST['arrival_time'];

    // Include current date for datetime columns
    $current_date = date('Y-m-d');
    $formatted_schedule_time = $current_date . ' ' . $schedule_time . ':00'; // Format as Y-m-d H:i:s
    $formatted_arrival_time = $current_date . ' ' . $arrival_time . ':00';   // Format as Y-m-d H:i:s

    // Construct the route
    $route = $destination_from . " to " . $destination_to;

    // Update query
    $updateQuery = "UPDATE rides SET plate_number = ?, ride_type = ?, capacity = ?, route = ?, time = ?, departure = ? WHERE ride_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssisisi", $plate_number, $ride_type, $seating_capacity, $route, $formatted_schedule_time, $formatted_arrival_time, $ride_id);

    if ($stmt->execute()) {
        echo "<script>alert('Ride updated successfully!'); window.location.href = 'route.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating ride: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Update Route</title>
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
        <div class="main-content">
            <form class="update-form" method="POST">
                <h2 class="form-title">UPDATE ROUTE</h2>
                
                <div class="form-group">
                    <label for="place_number">Plate Number</label>
                    <input type="text" id="place_number" name="place_number" value="<?= htmlspecialchars($ride['plate_number']) ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bus_type">Type of Ride</label>
                        <select id="bus_type" name="bus_type" required>
                            <option value="">Select Type</option>
                            <option value="Jeepney" <?= $ride['ride_type'] === 'Jeepney' ? 'selected' : '' ?>>Jeepney</option>
                            <option value="Service" <?= $ride['ride_type'] === 'Service' ? 'selected' : '' ?>>Service</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="destination_to">Destination To</label>
                        <input type="text" id="destination_to" name="destination_to" value="<?= explode(' to ', $ride['route'])[1] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="destination_from">Destination From</label>
                        <input type="text" id="destination_from" name="destination_from" value="<?= explode(' to ', $ride['route'])[0] ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="schedule_time">Schedule Time</label>
                        <input type="time" id="schedule_time" name="schedule_time" value="<?= htmlspecialchars(date('H:i', strtotime($ride['time']))) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="arrival_time">Arrival Time</label>
                        <input type="time" id="arrival_time" name="arrival_time" value="<?= htmlspecialchars(date('H:i', strtotime($ride['departure']))) ?>" required>

                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-update">UPDATE</button>
                    <button type="button" class="btn btn-back" onclick="history.back()">BACK</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

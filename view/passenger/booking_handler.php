<?php
require_once('../../control/includes/db.php');
header('Content-Type: application/json');

$rides = [];

// Check if the request is AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $isAjax) {
    if (!isset($_POST['route'], $_POST['ride_type'], $_POST['time'])) {
        echo json_encode(["error" => "Invalid input"]);
        exit;
    }

    $route = $_POST['route'];
    $ride_type = $_POST['ride_type'];
    $time = $_POST['time'];

    // Base query to exclude rides with seats_available <= 0
    $query = "SELECT route, TIME_FORMAT(time, '%h:%i %p') as formatted_time, seats_available, ride_type, ride_id 
              FROM rides WHERE seats_available > 0";
    $params = [];
    $types = '';

    // Route filter
    if (!empty($route)) {
        $query .= " AND LOWER(route) LIKE LOWER(?)";
        $params[] = '%' . $route . '%';
        $types .= 's';
    }

    // Ride type filter
    if ($ride_type !== 'All') {
        $query .= " AND ride_type = ?";
        $params[] = $ride_type;
        $types .= 's';
    }

    // Time filter
    if ($time !== 'All') {
        $start_time = date('H:i:s', strtotime($time));
        $end_time = date('H:i:s', strtotime('+59 minutes', strtotime($time)));
        $query .= " AND TIME(time) BETWEEN ? AND ?";
        $params[] = $start_time;
        $params[] = $end_time;
        $types .= 'ss';
    }

    // Execute query
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $rides = $result->fetch_all(MYSQLI_ASSOC);
    }

    $stmt->close();
}

echo json_encode($rides);
$conn->close();
?>
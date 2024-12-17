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

    // Base query
    $query = "SELECT * FROM rides WHERE 1";
    $params = [];
    $types = '';

    // Route filter
    if (!empty($route)) {
        if (stripos($route, 'bakakeng') !== false && stripos($route, 'igorot') === false) {
            $query .= " AND LOWER(route) LIKE LOWER(?)";
            $params[] = '%bakakeng to igorot park%';
        } elseif (stripos($route, 'igorot') !== false && stripos($route, 'bakakeng') === false) {
            $query .= " AND LOWER(route) LIKE LOWER(?)";
            $params[] = '%igorot park to bakakeng%';
        } else {
            $query .= " AND LOWER(route) LIKE LOWER(?)";
            $params[] = '%' . $route . '%';
        }
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
        $end_time = date('H:i:s', strtotime('+59 minutes 59 seconds', strtotime($start_time)));
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
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php');
header('Content-Type: application/json');

// Initialize an empty array for rides
$rides = [];

// Check if the request is valid
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER["REQUEST_METHOD"] === "POST" && $isAjax) {
    // Query to get rides with formatted time
    $query = "
        SELECT ride_id, route, TIME_FORMAT(time, '%h:%i %p') AS formatted_time, 
               seats_available, ride_type 
        FROM rides 
        WHERE seats_available > 0
    ";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $rides[] = [
                'ride_id' => $row['ride_id'],
                'route' => $row['route'],
                'time' => $row['formatted_time'], // Formatted time in 12-hour format
                'seats_available' => $row['seats_available'],
                'ride_type' => $row['ride_type']
            ];
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Failed to prepare SQL statement"]);
        exit;
    }
}

// Return JSON response
echo json_encode($rides);
$conn->close();
?>

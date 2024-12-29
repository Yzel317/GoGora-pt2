<?php
session_start();
require_once('../../control/includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /view/manager/manage.php");
    exit();
}

// Verify required session data is set
if (!isset($_SESSION['ride_id'], $_POST['payment_method'], $_SESSION['user_id'])) {
    header("Location: booking.php");
    exit();
}

// Extract session and POST data
$ride_id = $_SESSION['ride_id'];
$user_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method']; // Payment method selected by user
$payment_status = ($payment_method === 'GCash') ? 'Successful' : 'Pending';
$plate_number = $_SESSION['plate_number'];
$departure = $_SESSION['departure'];
$capacity = $_SESSION['capacity'];
$route = $_SESSION['route'];
$total_fare = $_SESSION['total_fare'] ?? 15;
$status = 'Active';

// Insert reservation into the database
$reservation_time = date('Y-m-d H:i:s');
$query = "
    INSERT INTO reservations (user_id, ride_id, reservation_time, status, payment_status, total_fare, payment_method)
    VALUES (?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("iisssis", $user_id, $ride_id, $reservation_time, $status, $payment_status, $total_fare, $payment_method);
if ($stmt->execute()) {
    // Update available seats
    $updateQuery = "UPDATE rides SET seats_available = seats_available - 1 WHERE ride_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $ride_id);
    $updateStmt->execute();

    // Fetch queue position
    $queueQuery = "SELECT COUNT(*) AS queue FROM reservations WHERE ride_id = ? AND status = 'Active'";
    $queueStmt = $conn->prepare($queueQuery);
    $queueStmt->bind_param("i", $ride_id);
    $queueStmt->execute();
    $queueResult = $queueStmt->get_result();
    $row = $queueResult->fetch_assoc();
    $queue = $row['queue'] ?? 1;

    // Pass queue and payment details to session for confirmation page
    $_SESSION['queue'] = $queue;
    $_SESSION['payment_status'] = $payment_status;
    $_SESSION['payment_method'] = $payment_method;

    // Redirect to GCash page if GCash is selected, else confirmation page
    if ($payment_method === 'GCash') {
        header("Location: gcash.php");
    } else {
        header("Location: confirmation.php");
    }
    exit();
} else {
    header("Location: booking.php");
    exit();
}

$stmt->close();
$conn->close();
?>

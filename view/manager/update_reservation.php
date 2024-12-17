<?php
include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php');

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Fetch reservation details
    $reservationQuery = "SELECT * FROM booking WHERE reservation_id = ?";
    $stmt = $conn->prepare($reservationQuery);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $reservationResult = $stmt->get_result();
    $reservation = $reservationResult->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update reservation logic
    $route = $_POST['route'];
    $schedule_time = $_POST['schedule_time'];
    $departure_time = $_POST['departure_time'];
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];

    $updateQuery = "UPDATE booking SET route = ?, schedule_time = ?, departure_time = ?, status = ?, payment_status = ? WHERE reservation_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssi", $route, $schedule_time, $departure_time, $status, $payment_status, $reservation_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation updated successfully!'); window.location.href = 'reservations.php';</script>";
    } else {
        echo "<script>alert('Error updating reservation: " . $conn->error . "');</script>";
    }
    $stmt->close();
}
?>

<!-- Update Form -->
<form method="POST">
    <label for="route">Route:</label>
    <input type="text" id="route" name="route" value="<?= htmlspecialchars($reservation['route']) ?>" required>

    <label for="schedule_time">Schedule Time:</label>
    <input type="datetime-local" id="schedule_time" name="schedule_time" value="<?= htmlspecialchars($reservation['schedule_time']) ?>" required>

    <label for="departure_time">Departure Time:</label>
    <input type="datetime-local" id="departure_time" name="departure_time" value="<?= htmlspecialchars($reservation['departure_time']) ?>" required>

    <label for="status">Status:</label>
    <input type="text" id="status" name="status" value="<?= htmlspecialchars($reservation['status']) ?>" required>

    <label for="payment_status">Payment Status:</label>
    <input type="text" id="payment_status" name="payment_status" value="<?= htmlspecialchars($reservation['payment_status']) ?>" required>

    <button type="submit">Update Reservation</button>
</form>

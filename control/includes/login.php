<?php
require('db.php');
session_start();

// Check if a session already exists and redirect to booking.php
if (isset($_SESSION['user_id'])) {
    header("Location: ../passenger/booking.php");
    exit;
}

// Retrieve the username and password from the form submission
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Prepare an SQL statement to select user data where the username matches
$loginSQL = "SELECT user_id, password, username, role, user_type FROM users WHERE username = ?";

$loginstmt = $conn->prepare($loginSQL); // Prepare the SQL statement
$loginstmt->bind_param("s", $username); // Bind the username to the prepared statement
$loginstmt->execute(); // Execute the statement

$result = $loginstmt->get_result(); // Get the result of the query

// Check if the user exists in the database
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Fetch the row of data for the user

    // Verify the input password matches the stored password (using hashing)
    if (password_verify($password, $row['password'])) {
        // Successful login, set session variables
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_type'] = $row['user_type'];

        // Redirect to booking.php
        header("Location: booking.php");
        exit;
    } else {
        // Invalid password
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$loginstmt->close();
$conn->close();
?>

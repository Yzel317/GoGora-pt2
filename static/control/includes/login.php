<?php
require('db.php');
session_start();

// Check if a session already exists and redirect to booking.php
if (isset($_SESSION['user_id'])) {
    header("Location: ../../view/passenger/booking.php");
    exit;
}

// Retrieve the username and password from the form submission
$username = strtolower(trim($_POST['username'] ?? ''));
$password = trim($_POST['password'] ?? '');

// Prepare an SQL statement to select user data where the username matches
$loginSQL = "SELECT user_id, password, username, role, user_type FROM users WHERE username = ?";

$loginstmt = $conn->prepare($loginSQL); // Prepare the SQL statement
$loginstmt->bind_param("s", $username); // Bind the username to the prepared statement
$loginstmt->execute(); // Execute the statement

$result = $loginstmt->get_result(); // Get the result of the query

var_dump($_POST);

// Check if the user exists in the database
if ($result->num_rows > 0) {
    $column = $result->fetch_assoc(); // Fetch the row of data for the user
    var_dump($column);

    // Verify the input password matches the stored password (using hashing)
    if (password_verify($password, $column['password'])) {
        // Successful login, set session variables
        $_SESSION['user_id'] = $column['user_id'];
        $_SESSION['username'] = $column['username'];
        $_SESSION['role'] = $column['role'];
        $_SESSION['user_type'] = $column['user_type'];

        // Redirect to booking.php
        // Redirect based on role
        if ($user['role'] == 'Student') {
            header("Location: /view/passenger/booking.php");
        } else if ($user['role'] == 'Manager') {
            header("Location: /view/manager/dashboard.php");
        } else if ($user['role'] == 'Faculty') {
            header("Location: /view/passenger/booking.php");
        } else if ($user['role'] == 'Employee') {
            header("Location: /view/passenger/booking.php");
        }
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

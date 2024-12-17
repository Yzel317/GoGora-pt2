<!-- Frontend by: Jekka Hufalar
     Backend by: Mark Jervin Galarce -->
<?php
require_once('db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Capture form data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$userType = $_POST['userType'] ?? 'Regular';

// Basic validation
if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($role)) {
    die("All fields are required.");
}

// Check if username or email already exists
$checkQuery = "SELECT COUNT(*) FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    die("Username or email already exists.");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert data into the database
$insertQuery = "INSERT INTO users (firstname, lastname, email, username, password, role, user_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sssssss", $firstName, $lastName, $email, $username, $hashedPassword, $role, $userType);

if ($stmt->execute()) {
    // Redirect to login form after successful registration
    header("Location: ../../view/passenger/login.php");
    exit(); // Stop further execution
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

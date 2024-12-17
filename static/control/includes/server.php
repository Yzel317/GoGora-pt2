<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gogora_images";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update avatar in the database
function updateAvatar($user_id, $selected_avatar) {
    global $conn;
    $sql = "UPDATE users SET avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $selected_avatar, $user_id);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Avatar updated successfully'];
    } else {
        return ['success' => false, 'message' => 'Error updating avatar: ' . $conn->error];
    }
}

// Function to get the user's current avatar from the database
function getUserAvatar($user_id) {
    global $conn;
    $sql = "SELECT avatar FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['avatar'] ?? null;
}

// Function to verify the user (e.g., marking them as verified)
function verifyUser($user_id) {
    global $conn;
    $sql = "UPDATE users SET verified = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// Function to retrieve all available avatars from the database
function getAllAvatars() {
    global $conn;
    $sql = "SELECT * FROM avatars"; // Assuming an `avatars` table
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to register a user (can be used for registration form processing)
function registerUser($first_name, $last_name, $email, $username, $password, $role, $user_type) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (first_name, last_name, email, username, password, role, user_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $username, $hashed_password, $role, $user_type);
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Registration successful.'];
    } else {
        return ['success' => false, 'message' => 'Registration failed: ' . $conn->error];
    }
}

// Function to authenticate user (for login)
function authenticateUser($username, $password) {
    global $conn;
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return ['success' => true, 'user_id' => $user['id']];
        } else {
            return ['success' => false, 'message' => 'Incorrect password'];
        }
    } else {
        return ['success' => false, 'message' => 'User not found'];
    }
}

// Function to fetch a user by their ID (for profile purposes)
function getUserById($user_id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Optional: Close the database connection after script execution (if necessary)
function closeDbConnection() {
    global $conn;
    $conn->close();
}

?>
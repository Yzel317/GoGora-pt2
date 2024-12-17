<?php
include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php'); // Include database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch user data to edit
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        echo "<script>alert('User not found!'); window.location.href = 'account.php';</script>";
        exit;
    }
    $stmt->close();
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $user_type = $_POST['user_type'];

    // Check if the new username or email already exists in the database
    $checkQuery = "SELECT username, email FROM users WHERE (username = ? OR email = ?) AND username != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("sss", $new_username, $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Username or email already exists!.');</script>";
    } else {
        $updateQuery = "UPDATE users SET username = ?, firstname = ?, lastname = ?, email = ?, role = ?, user_type = ? WHERE username = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssss", $new_username, $firstname, $lastname, $email, $role, $user_type, $username);

        if ($stmt->execute()) {
            echo "<script>alert('User updated successfully!'); window.location.href = 'account.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error updating user: " . $conn->error . "');</script>";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Edit User</title>
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
                <h2 class="form-title">EDIT USER</h2>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Student" <?= $user['role'] === 'Student' ? 'selected' : '' ?>>Student</option>
                            <option value="Faculty" <?= $user['role'] === 'Faculty' ? 'selected' : '' ?>>Faculty</option>
                            <option value="Employee" <?= $user['role'] === 'Employee' ? 'selected' : '' ?>>Employee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_type">User Type</label>
                        <select id="user_type" name="user_type" required>
                            <option value="">Select Type</option>
                            <option value="Regular" <?= $user['user_type'] === 'Regular' ? 'selected' : '' ?>>Regular</option>
                            <option value="Priority" <?= $user['user_type'] === 'Priority' ? 'selected' : '' ?>>Priority</option>
                        </select>
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

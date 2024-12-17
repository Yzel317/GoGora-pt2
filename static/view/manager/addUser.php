<?php
include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php');  // Include database connection

// Handle form submission and add user logic
$successMessage = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password
    $email = $_POST['email'];
    $role = $_POST['role'];
    $user_type = $_POST['user_type'];

    // Check if the username or email is already taken
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    if (!$stmtCheck) {
        $errorMessage = "Error preparing statement: " . $conn->error;
    } else {
        $stmtCheck->bind_param("ss", $username, $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $errorMessage = "Username or email is already taken. Please choose another.";
            
        } else {
            // Insert user into the database
            $query = "INSERT INTO users (username, firstname, lastname, password, email, role, user_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                $errorMessage = "Error preparing statement: " . $conn->error;
            } else {
                $stmt->bind_param("sssssss", $username, $firstname, $lastname, $password, $email, $role, $user_type);
                if ($stmt->execute()) {
                    $successMessage = "User added successfully!";
                } else {
                    $errorMessage = "Failed to add user: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        $stmtCheck->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Add User</title>
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
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <form class="update-form" method="POST" action="">
                <h2 class="form-title">ADD A USER</h2>

                <!-- Success and Error Messages -->
                <?php if ($successMessage): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="display: flex; align-items: center;">
                        <input type="password" id="password" name="password" required style="flex: 1;">
                        <button type="button" class="btn btn-show-password" onclick="togglePassword()" style="margin-left: 10px;">Show</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Student">Student</option>
                            <option value="Faculty">Faculty</option>
                            <option value="Employee">Employee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_type">User Type</label>
                        <select id="user_type" name="user_type" required>
                            <option value="">Select Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Priority">Priority</option>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-add">ADD USER</button>
                    <button type="button" class="btn btn-back" onclick="history.back()">BACK</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const showButton = document.querySelector('.btn-show-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                showButton.textContent = 'Show';
            }
        }
    </script>
</body>
</html>

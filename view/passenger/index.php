<?php
require_once('../../control/includes/db.php'); // Adjust the path if needed
session_start();

$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $role = trim($_POST['role']);

    // Check if email has '@manager' to set role and user type
    if (strpos($email, '@manager') !== false) {
        $role = 'Manager';
        $userType = 'priority';
    } else {
        // Determine user type based on role
        $userType = ($role === 'Faculty' || $role === 'Employee') ? 'priority' : 'regular';
    }

    // Validate passwords
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match!";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = "Password needs at least 1 special character!";
    }
    if (!preg_match('/\d/', $password)) {
        $errors[] = "Password needs at least 1 number!";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password needs to be at least 8 characters long!";
    }

    // Check for duplicate username
    if (empty($errors)) {
        $query = "SELECT COUNT(*) AS count FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            $errors[] = "Username already exists. Please choose another.";
        }
        $stmt->close();
    }

    // If no errors, save the data to the database
    if (empty($errors)) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into the database
        $query = "INSERT INTO users (firstname, lastname, email, username, password, role, user_type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param(
                "sssssss",
                $firstName,
                $lastName,
                $email,
                $username,
                $hashedPassword,
                $role,
                $userType
            );

            if ($stmt->execute()) {
                // Redirect based on user role
                if ($role === 'Manager') {
                  header("Location: ../manager/manage.php");  // Manager dashboard
                } else {
                    header("Location: ../manager/manage.php"); // Default page for others
                }
                exit();
            } else {
                $errors[] = "Failed to register. Please try again.";
            }
            $stmt->close();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora</title>
    <link rel="icon" type="image/png" href="assets/favicon.png"> 
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <div class="left-section">
        <div class="promo-content">
            <img src="/GoGora-pt2/view/assets/logo.png" alt="GoGora Logo" class="promo-logo">
            <h1 class="promo-title">GoGora</h1>
            <p class="promo-subtitle">Your journey, our pride!</p>
        </div>
    </div>
    <div class="right-section">
        <form id="registration-form" method="POST" action="">
            <h2>Get Started Today</h2>
            
            <!-- First Name and Last Name -->
            <div class="input-group name-group">
                <div class="first-name">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="firstName" placeholder="Enter your first name" 
                           value="<?php echo htmlspecialchars($firstName ?? ''); ?>" required>
                </div>
                <div class="last-name">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="lastName" placeholder="Enter your last name" 
                           value="<?php echo htmlspecialchars($lastName ?? ''); ?>" required>
                </div>
            </div>

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>

            <!-- Username -->
            <div class="input-group">
                   <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" autocomplete="off" required>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter a password" required>
            </div>

            <!-- Confirm Password -->
            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirmPassword" placeholder="Re-enter your password" required>
                <label for="role">Select Role</label>
                <select id="role" name="role" required>
                    <option value="Student" <?php echo isset($role) && $role === 'Student' ? 'selected' : ''; ?>>Student</option>
                    <option value="Faculty" <?php echo isset($role) && $role === 'Faculty' ? 'selected' : ''; ?>>Faculty</option>
                    <option value="Employee" <?php echo isset($role) && $role === 'Employee' ? 'selected' : ''; ?>>Employee</option>
                </select>
            </div>
            <!-- Display Validation Errors -->
            <?php if (!empty($errors)) { ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error) { ?>
                        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <button type="submit" class="register-btn">Register</button>
            <p class="login-text">Already a member? <a href="../manager/manage.php">Log in</a></p>
        </form>
    </div>
</div>
</body>
</html>

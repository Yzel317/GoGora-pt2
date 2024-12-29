<!-- Frontend by: Jekka Hufalar
    Backend by: Mark Jervin Galarce, Justine Lucas-->
<?php
session_start(); // Start session at the top

include('../../control/includes/db.php');


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Query to check if username exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, create session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_type'] = $user['user_type'];

                // Redirect based on role
                if (in_array($user['role'], ['Student', 'Faculty', 'Employee'])) {
                    header("Location: /GoGora-pt2/view/passenger/booking.php");
                } elseif ($user['role'] === 'Manager') {
                    header("Location: /GoGora-pt2/view/manager/dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Username not found.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora</title>
    <link rel="icon" type="image/png" href="/GoGora-pt2/view/assets/favicon.png">
    <link rel="stylesheet" href="manager.css">
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
        <!-- Registration Form -->
        <form class="registration-form" method="POST" autocomplete="off">
          <h2>Welcome Here in GoGora</h2>
          <div class="input-group">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" placeholder="Input your username" required>
          </div>
          <div class="input-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
      
          <!-- Error Message Below Password Input -->
          <?php if (isset($error)) { ?>
              <p class="error-message"><?php echo $error; ?></p>
          <?php } ?>
          
          <button type="submit" class="register-btn">Login</button>
          <p class="login-text">Don't have an account? <a href="/GoGora-pt2/view/passenger/index.php">Sign Up</a></p>
        </form>
</body>
</html>
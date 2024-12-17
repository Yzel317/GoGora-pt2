<!-- Frontend by: John Gabriel Pampo 
     Backend by: Jemma Niduaza and Mark Jervin Galarce -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png"> 
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div class="container">
      <div class="left-section">
        <div class="promo-content">
          <img src="../assets/logo.png" alt="GoGora Logo" class="promo-logo">
          <h1 class="promo-title">GoGora</h1>
          <p class="promo-subtitle">Your journey, our pride!</p>
        </div>
      </div>
      <div class="right-section">
      <!-- This is the registration form of the web application -->
        <form id="registration-form" method="POST" action="../../control/includes/register.php">
    <h2>Get Started Today</h2>
    <div class="input-group name-group">
        <div class="first-name">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="firstName" placeholder="Enter your first name" required>
        </div>
        <div class="last-name">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="lastName" placeholder="Enter your last name" required>
        </div>
    </div>
    <div class="input-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Choose a username" required>
    </div>
    <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter a password" required>
    </div>
    <div class="input-group">
        <label for="role">Select Role</label>
        <select id="role" name="role" required>
            <option value="Student">Student</option>
            <option value="Faculty">Faculty</option>
            <option value="Employee">Employee</option>
        </select>
    </div>
    <div class="input-group">
        <label for="user-type">User Type</label>
        <select id="user-type" name="userType" required>
            <option value="Regular">Regular</option>
            <option value="Priority">Priority</option>
        </select>
    </div>
    <button type="submit" class="register-btn">Register</button>
    <p class="login-text">Already a member? <a href="#" id="show-login-form">Log in</a></p>
</form>

    <!-- This is the login form of the web application -->
<div class="form-container" id="login-form" style="display: none;">
  <h2>Welcome Back</h2>
  <!-- Login Form -->
  <form id="login-form-element" method="POST" action="../../control/includes/login.php">
    <div class="input-group">
      <label for="login-username">Username</label>
      <input type="text" id="login-username" name="username" placeholder="Enter your username" required>
    </div>
    <div class="input-group">
      <label for="login-password">Password</label>
      <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
    </div>
    <button type="submit" class="register-btn" id="login-btn">Log In</button>
  </form>
  <p class="login-text">Don't have an account? <a href="#" id="show-register-form">Sign Up</a></p>
</div>
  <!-- This is the form for the avatar upload  of the web application -->
        <!-- Photo Upload Form -->
        <!-- <div class="form-container" id="upload-photo-form" style="display: none;">
          <h2>Upload Your Photo</h2>
          <form id="photo-upload-form" action="verification.html" method="POST" enctype="multipart/form-data">
            <div class="input-group">
              <label for="photo">Choose Photo</label>
              <input type="file" name="photo" id="photo" accept="image/*" required>
            </div>
            <button type="submit" class="register-btn">Submit Photo</button>
          </form>
        </div> -->
      </div>
    </div>
    <script src="script.js"></script>
  </body>
</html>
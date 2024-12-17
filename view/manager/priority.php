<?php
include($_SERVER['DOCUMENT_ROOT'] . '/GoGora/control/includes/db.php'); // Include database connection

// Fetch all priority users
$priorityUsersQuery = "SELECT firstname, lastname, username, role, user_type FROM users WHERE user_type = 'Priority'";
$priorityUsersResult = mysqli_query($conn, $priorityUsersQuery);

// Fetch all rides for ride management
$ridesQuery = "SELECT ride_id, route, time, seats_available FROM rides";
$ridesResult = mysqli_query($conn, $ridesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Manage Priority Lane</title>
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
        <main class="content">
            <header>
                <h2>Manage Priority Lane</h2>
                <a href="../manager/dashboard.php" class="back-link">Back to Dashboard</a>
            </header>
            <section class="accounts">
                <div class="section-header">
                    <h3>Priority User Management</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Passenger</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($priorityUser = mysqli_fetch_assoc($priorityUsersResult)): ?>
                            <tr>
                                <td><?= htmlspecialchars($priorityUser['firstname'] . ' ' . $priorityUser['lastname']) ?></td>
                                <td><?= htmlspecialchars($priorityUser['username']) ?></td>
                                <td><?= htmlspecialchars($priorityUser['role']) ?></td>
                                <td><?= htmlspecialchars($priorityUser['user_type']) ?></td>
                                <td>
                                    <button class="action-btn edit" onclick="editUser('<?= $priorityUser['username'] ?>')">✏️</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>
        function editUser(username) {
            window.location.href = `editUser.php?username=${username}`;
        }
        function editRide(rideId) {
            window.location.href = `editRide.php?ride_id=${rideId}`;
        }
    </script>
</body>
</html>

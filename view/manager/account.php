<?php
include($_SERVER['DOCUMENT_ROOT'] . '/control/includes/db.php');

// Handle delete request for users
if (isset($_GET['delete_user'])) {
    $username = $_GET['delete_user'];

    // Step 1: Delete the user from the blacklist if it exists
    $deleteBlacklistQuery = "DELETE FROM blacklist WHERE username = ?";
    $stmtBlacklist = $conn->prepare($deleteBlacklistQuery);
    if (!$stmtBlacklist) {
        echo "<script>alert('Error preparing blacklist deletion: " . $conn->error . "'); window.location.href = 'account.php';</script>";
        exit;
    }
    $stmtBlacklist->bind_param("s", $username);
    $stmtBlacklist->execute();
    $stmtBlacklist->close();


    $deleteUserQuery = "DELETE FROM users WHERE username = ?";
    $stmtUser = $conn->prepare($deleteUserQuery);
    if (!$stmtUser) {
        echo "<script>alert('Error preparing user deletion: " . $conn->error . "'); window.location.href = 'account.php';</script>";
        exit;
    }
    $stmtUser->bind_param("s", $username);

    if ($stmtUser->execute()) {
        // echo "<script>alert('User deleted successfully!'); window.location.href = 'account.php';</script>";
    } else {
        // echo "<script>alert('Error deleting user: " . $stmtUser->error . "'); window.location.href = 'account.php';</script>";
    }
    $stmtUser->close();
}

// Handle delete request for blacklist
if (isset($_GET['delete_blacklist'])) {
    $username = $_GET['delete_blacklist'];
    $deleteBlacklistQuery = "DELETE FROM blacklist WHERE username = ?";
    $stmt = $conn->prepare($deleteBlacklistQuery);
    if (!$stmt) {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href = 'account.php';</script>";
        exit;
    }
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // echo "<script>alert('Blacklist entry deleted successfully!'); window.location.href = 'account.php';</script>";
    } else {
        // echo "<script>alert('Error deleting blacklist entry: " . $stmt->error . "'); window.location.href = 'account.php';</script>";
    }
    $stmt->close();
}

// Handle adding to blacklist
if (isset($_GET['blacklist_user'])) {
    $username = $_GET['blacklist_user'];
    $reason = $_GET['reason'];
    $date = date('Y-m-d H:i:s');

    // Check if the user is already blacklisted
    $checkBlacklistQuery = "SELECT * FROM blacklist WHERE username = ?";
    $stmtCheck = $conn->prepare($checkBlacklistQuery);
    if (!$stmtCheck) {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href = 'account.php';</script>";
        exit;
    }
    $stmtCheck->bind_param("s", $username);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo "<script>alert('Error: User is already blacklisted.'); window.location.href = 'account.php';</script>";
        $stmtCheck->close();
        exit;
    }
    $stmtCheck->close();

    // Add user to the blacklist
    $addBlacklistQuery = "INSERT INTO blacklist (username, blacklist_date, blacklist_status, reason) VALUES (?, ?, 'Blacklisted', ?)";
    $stmt = $conn->prepare($addBlacklistQuery);
    if (!$stmt) {
        echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href = 'account.php';</script>";
        exit;
    }
    $stmt->bind_param("sss", $username, $date, $reason);

    if ($stmt->execute()) {
        // echo "<script>alert('User successfully blacklisted!'); window.location.href = 'account.php';</script>";
    } else {
        // echo "<script>alert('Error blacklisting user: " . $stmt->error . "'); window.location.href = 'account.php';</script>";
    }
    $stmt->close();
}

// Fetch all accounts
$accountsQuery = "SELECT firstname, lastname, username, role, user_type FROM users";
$accountsResult = $conn->query($accountsQuery);
if (!$accountsResult) {
    die("Error fetching accounts: " . $conn->error);
}

// Fetch all blacklisted users
$blacklistQuery = "SELECT b.username, u.firstname, u.lastname, b.reason, b.blacklist_date 
                   FROM blacklist b 
                   JOIN users u ON b.username = u.username";
$blacklistResult = $conn->query($blacklistQuery);
if (!$blacklistResult) {
    die("Error fetching blacklist: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Manage Accounts</title>
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
                <h2>Manage Accounts</h2>
                <a href="../manager/dashboard.php" class="back-link">Back to Dashboard</a>
            </header>
            <section class="accounts">
                <div class="section-header">
                    <h3>Accounts</h3>
                    <button class="add-user"><a href="../manager/addUser.php">Add User</a></button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($account = mysqli_fetch_assoc($accountsResult)): ?>
                            <tr>
                                <td><?= htmlspecialchars($account['firstname'] . ' ' . $account['lastname']) ?></td>
                                <td><?= htmlspecialchars($account['username']) ?></td>
                                <td><?= htmlspecialchars($account['role']) ?></td>
                                <td><?= htmlspecialchars($account['user_type']) ?></td>
                                <td>
                                    <button class="action-btn edit" onclick="editUser('<?= $account['username'] ?>')">✏️</button>
                                    <button class="action-btn delete" onclick="deleteUser('<?= $account['username'] ?>')">🗑️</button>
                                    <button class="action-btn blacklist" onclick="blacklistUser('<?= $account['username'] ?>')">🚫</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
            <section class="blacklisted">
                <div class="section-header">
                    <h3>Blacklisted Passengers</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reason</th>
                            <th>Blacklist Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($blacklist = mysqli_fetch_assoc($blacklistResult)): ?>
                            <tr>
                                <td><?= htmlspecialchars($blacklist['firstname'] . ' ' . $blacklist['lastname']) ?></td>
                                <td><?= htmlspecialchars($blacklist['reason']) ?></td>
                                <td><?= htmlspecialchars(date('F d, Y', strtotime($blacklist['blacklist_date']))) ?></td>
                                <td>
                                    <button class="action-btn delete" onclick="deleteBlacklist('<?= $blacklist['username'] ?>')">🗑️</button>
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
        function deleteUser(username) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = `account.php?delete_user=${username}`;
            }
        }
        function blacklistUser(username) {
            const reason = prompt('Enter reason for blacklisting:');
            if (reason) {
                window.location.href = `account.php?blacklist_user=${username}&reason=${encodeURIComponent(reason)}`;
            }
        }
        function deleteBlacklist(username) {
            if (confirm('Are you sure you want to remove this blacklist?')) {
                window.location.href = `account.php?delete_blacklist=${username}`;
            }
        }
    </script>
</body>
</html>

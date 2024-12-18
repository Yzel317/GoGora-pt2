<!-- Authors: Jemma Niduaza, Mark Jervin Galarce -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora - Booking Logs</title>
    <link rel="icon" type="image/png" href="/view/assets/favicon.png">
    <link rel="stylesheet" href="style2.css">
</head>
<body id="book-bod">
    <header class="book-head">
        <h1>GoGora</h1>
        <a href="#"><img src="/view/assets/profile.png" alt="Profile"></a>
        <div class="logout-link">
            <a href="/view/manager/manage.php"><img src="/view/assets/logout.png"></a>
        </div>
    </header>

    <div class="book-cont">
        <form id="filterForm">
            <label for="route">Search Route:</label>
            <input type="text" name="route" id="route" placeholder="Enter route name">

            <label for="ride_type">Select Ride Type:</label>
            <select name="ride_type" id="ride_type">
                <option value="All">All types</option>
                <option value="Jeepney">Jeepney</option>
                <option value="Service">Service</option>
            </select>

            <label for="time">Select Time:</label>
            <select name="time" id="time">
                <option value="All">All Times</option>
                <?php
                    for ($hour = 7; $hour <= 19; $hour++) {
                        $time_option = sprintf('%02d:00:00', $hour);
                        $display_time = date('g:i A', strtotime($time_option));
                        echo "<option value='$time_option'>$display_time</option>";
                    }
                ?>
            </select>

            <button type="submit">Search</button>
            <button type="button" onclick="window.location.reload();">Clear Filter</button>
            <button type="button" onclick="window.history.back();">Back</button>
        </form>

        <h1>Booking Logs</h1>
        <section class="ride-list" id="rideListContainer">
            <p>Loading booking logs...</p>
        </section>
    </div>
</body>
</html>

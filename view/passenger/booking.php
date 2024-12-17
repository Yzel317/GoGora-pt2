<!-- Frontend by: Chryzel Beray, Mark Jervin Galarce
     Backend by: Mark Jervin Galarce, Jekka Hufalar, Justine Lucas, and Jemma Niduaza -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora Booking</title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="stylesheet" href="style2.css">
</head>
<body id="book-bod">
    <header class="book-head">
        <h1>GoGora</h1>
        <a href="#"><img src="assets/profile.png" alt="Profile"></a>
        <div class="logout-link">
            <a href="includes/index.php"><img src="assets/logout.png"></a>
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
        </form>

        <h1>Choose a Ride</h1>
        <section class="ride-list" id="rideListContainer">
            <p>Loading available rides...</p>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const rideListContainer = document.getElementById("rideListContainer");
            const filterForm = document.getElementById("filterForm");

function fetchRides() {
                fetch("booking_handler.php", {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                    body: new FormData(filterForm)
                })
                .then(response => response.json())
                .then(data => {
                    rideListContainer.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(ride => {
                            const rideItem = document.createElement("div");
                            rideItem.classList.add("ride-item");

                            const formattedTime = new Date('1970-01-01T' + ride.time.split(' ')[1] + 'Z')
                                .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                            rideItem.innerHTML = `
                                <div class="ride-info">
                                    <p>Route: ${ride.route}</p>
                                    <p>Time: ${formattedTime}</p>
                                    <p>Seats Available: ${ride.seats_available}</p>
                                    <p>Ride Type: ${ride.ride_type}</p>
                                    <form method="POST" action="details.php">
                                        <input type="hidden" name="ride_id" value="${ride.ride_id}">
                                        <button type="submit" class="book-btn">Book</button>
                                    </form>
                                </div>
                            `;
                            rideListContainer.appendChild(rideItem);
                        });
                    } else {
                        rideListContainer.innerHTML = "<p>No rides available at the moment.</p>";
                    }
                })
                .catch(error => {
                    console.error("Error fetching rides:", error);
                    rideListContainer.innerHTML = "<p>Error loading rides. Please try again later.</p>";
                });
            }

            filterForm.addEventListener("submit", function (event) {
                event.preventDefault();
                fetchRides();
            });

            fetchRides();
        });
    </script>
</body>
</html>

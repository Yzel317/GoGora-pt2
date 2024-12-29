<!-- Frontend by: Chryzel Beray, Mark Jervin Galarce
     Backend by: Mark Jervin Galarce, Jekka Hufalar, Justine Lucas, and Jemma Niduaza -->
     <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora Booking</title>
    <link rel="icon" type="image/png" href="/GoGora-pt2/view/assets/favicon.png">
    <link rel="stylesheet" href="style2.css">
</head>
<body id="book-bod">
<header class="book-head">
    <div class="logo-section">
        <img src="/GoGora-pt2/view/assets/favicon.png" alt="Jeep Logo">
        <h1>Go<span style="color: #FDBE34;">Gora</span></h1>
    </div>
    <div class="profile-section">
        <a href="#"><img src="/GoGora-pt2/view/assets/profile.png" alt="Profile"></a>
        <a href="/GoGora-pt2/view/manager/manage.php" class="logout-link">
            <img src="/GoGora-pt2/view/assets/logout.png" alt="Logout">
        </a>
    </div>
</header>



    <main class="main-container">
        <div class="button-container">
            <button type="button" onclick="window.location.href='bookLogs.php';" class="top-button">History</button>
            <button type="button" onclick="window.location.href='bookHistory.php';" class="top-button">Bookings</button>
        </div>

        <div class="book-cont">
            <form id="filterForm" class="filter-form">
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

                <div class="form-buttons">
                    <button type="submit" class="filter-button">Search</button>
                    <button type="button" onclick="window.location.reload();" class="filter-button">Clear Filter</button>
                </div>
            </form>

            <h1 class="ride-title">Choose a Ride</h1>
            <section class="ride-list" id="rideListContainer">
                <p class="loading-text">Loading available rides...</p>
            </section>
        </div>
    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const rideListContainer = document.getElementById("rideListContainer");
        const filterForm = document.getElementById("filterForm");

        function fetchRides() {
            rideListContainer.innerHTML = "<p class='loading-text'>Loading available rides...</p>";
            fetch("booking_handler.php", {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: new FormData(filterForm)
            })
            .then(response => response.json())
            .then(data => {
                rideListContainer.innerHTML = "";
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(ride => {
                        const rideItem = document.createElement("div");
                        rideItem.classList.add("ride-item");

                        rideItem.innerHTML = `
                            <div class="ride-info">
                                <p><strong>Route:</strong> ${ride.route}</p>
                                <p><strong>Time:</strong> ${ride.time}</p>
                                <p><strong>Seats Available:</strong> ${ride.seats_available}</p>
                                <p><strong>Ride Type:</strong> ${ride.ride_type}</p>
                                <form method="POST" action="details.php">
                                    <input type="hidden" name="ride_id" value="${ride.ride_id}">
                                    <button type="submit" class="book-btn">Book</button>
                                </form>
                            </div>
                        `;
                        rideListContainer.appendChild(rideItem);
                    });
                } else if (data.error) {
                    rideListContainer.innerHTML = `<p class='error-text'>${data.error}</p>`;
                } else {
                    rideListContainer.innerHTML = "<p class='no-rides'>No rides available at the moment.</p>";
                }
            })
            .catch(error => {
                console.error("Error fetching rides:", error);
                rideListContainer.innerHTML = "<p class='error-text'>Error loading rides. Please try again later.</p>";
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

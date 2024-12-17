<!-- Frontend by: Chryzel Beray, Mark Jervin Galarce
     Backend by: Mark Jervin Galarce, Jekka Hufalar, Justine Lucas, and Jemma Niduaza -->
     <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoGora Booking</title>
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link rel="stylesheet" href="style2.css">
</head>
<body id="book-bod">
    <header class="book-head">
        <h1>GoGora</h1>
        <a href="#"><img src="../assets/profile.png" alt="Profile"></a>
        <div class="logout-link">
            <a href="/index.html"><img src="../assets/logout.png"></a>
        </div>
    </header>

    <div class="book-cont">
        <h1>Choose a Ride</h1>
        <section class="ride-list" id="rideListContainer">
            <p>Loading available rides...</p>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const rideListContainer = document.getElementById("rideListContainer");

            function fetchRides() {
                fetch("booking_handler.php", {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                })
                .then(response => response.json())
                .then(data => {
                    rideListContainer.innerHTML = ""; // Clear existing content

                    if (data.length > 0) {
                        data.forEach(ride => {
                            const rideItem = document.createElement("div");
                            rideItem.classList.add("ride-item");

                            // Display formatted ride details
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
                    } else {
                        rideListContainer.innerHTML = "<p>No rides available at the moment.</p>";
                    }
                })
                .catch(error => {
                    console.error("Error fetching rides:", error);
                    rideListContainer.innerHTML = "<p>Error loading rides. Please try again later.</p>";
                });
            }

            // Fetch rides on page load
            fetchRides();
        });
    </script>
</body>
</html>

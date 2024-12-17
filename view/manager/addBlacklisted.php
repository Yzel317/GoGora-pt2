<!-- <?php
   include ('../GoGora-main/control/includes.php');
  
   ?> -->
   
   
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>GoGora - Add Blacklisted Passenger</title>
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
           <!-- MAIN CONTENT HERE -->
           <div class="main-content">
            <form class="update-form" method="POST" action="process-update.php">
                <h2 class="form-title">BLACKLIST A PASSENGER</h2>
                
                <div class="form-group">
                    <label for="place_number">Plate Number</label>
                    <input type="text" id="place_number" name="place_number" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bus_type">Type of Ride</label>
                        <select id="bus_type" name="bus_type" required>
                            <option value="">Select Type</option>
                            <option value="jeepney">Jeepney</option>
                            <option value="service">Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="seating_capacity">Seating Capacity</label>
                        <input type="number" id="seating_capacity" name="seating_capacity" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="destination_to">Destination To</label>
                        <input type="text" id="destination_to" name="destination_to" required>
                    </div>
                    <div class="form-group">
                        <label for="destination_from">Destination From</label>
                        <input type="text" id="destination_from" name="destination_from" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="schedule_time">Schedule Time</label>
                        <select id="schedule_time" name="schedule_time" required>
                            <option value="">Select Time</option>
                            <?php
                            for ($hour = 0; $hour < 24; $hour++) {
                                for ($minute = 0; $minute < 60; $minute += 30) {
                                    $time = sprintf("%02d:%02d", $hour, $minute);
                                    echo "<option value='$time'>$time</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="arrival_time">Arrival Time</label>
                        <select id="arrival_time" name="arrival_time" required>
                            <option value="">Select Time</option>
                            <?php
                            for ($hour = 0; $hour < 24; $hour++) {
                                for ($minute = 0; $minute < 60; $minute += 30) {
                                    $time = sprintf("%02d:%02d", $hour, $minute);
                                    echo "<option value='$time'>$time</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-update">UPDATE</button>
                    <button type="button" class="btn btn-back" onclick="history.back()">BACK</button>
                </div>
            </form>
        </div>
       </div>
   </body>
   </html>
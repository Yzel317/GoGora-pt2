// Author: Justine Lucas
const mysql = require('mysql2');
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'your_db_username',
  password: 'your_db_password',
  database: 'your_database_name'
});

// Establish the connection
connection.connect((err) => {
  if (err) {
    console.error("Connection failed:", err);
    process.exit(1); // Exit the process with error code 1
  }
  console.log("Connected to the database.");
});

// Query to fetch schedules (with optional join to rides)
const query = `
  SELECT 
    schedules.schedule_id,
    schedules.ride_id,
    schedules.date,
    rides.plate_number,
    rides.route
  FROM schedules
  LEFT JOIN rides ON schedules.ride_id = rides.ride_id
`;

// Execute the query
connection.query(query, (err, results) => {
  if (err) {
    console.error("Error fetching schedules:", err);
    connection.end();
    return;
  }

  // Prepare the response as JSON
  const schedules = results;

  // Return the response in JSON format
  console.log(JSON.stringify(schedules));
  
  // Close the database connection
  connection.end();
});

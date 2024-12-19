const express = require('express');
const path = require('path');
const app = express();

// Routes
const ridesRouter = require('./view/admin/api/rides'); 
const scheduleRouter = require('./view/admin/api/schedule');
const usersRouter = require('./view/admin/api/users');

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static files from the 'view/admin' directory for HTML, CSS, and JS
// This will serve index.html, admin.css, etc. from root path
app.use(express.static(path.join(__dirname, 'view/admin'))); // Serve index.html, admin.css, etc.

// Serve static files from the 'view/admin/api' directory for API JS
app.use('/api', express.static(path.join(__dirname, 'view/admin/api'))); // Serve script.js

// Serve static files (e.g., uploaded images)
app.use('/model/uploads', express.static(path.join(__dirname, 'uploads')));

// Routes setup
app.use('/api/rides', ridesRouter);
app.use('/api/schedule', scheduleRouter);
app.use('/api/users', usersRouter);

// Default route for catching any unhandled requests
app.use((req, res) => {
  res.status(404).send("Route not found");
});

// Route to add a schedule
/*app.post('/api/schedule', (req, res) => {
  const { route, departure, seats_available } = req.body;

  // Log the incoming data to see if the request is being handled
  console.log('Received data:', { route, departure, seats_available });

  if (!route || !departure || !seats_available) {
      return res.status(400).json({ error: 'route, Departure, and Seats Available are required' });
  }

  const query = 'INSERT INTO schedule (route, departure, seats_available) VALUES (?, ?, ?)';
  db.query(query, [route, departure, seats_available], (err, result) => {
      if (err) {
          console.error('Error inserting schedule:', err);
          return res.status(500).json({ error: 'Error adding schedule' });
      }

      console.log('Schedule inserted with ride_id:', result.insertId);
      res.status(201).json({ message: 'Schedule added successfully', ride_id: result.insertId });
  });
});
*/
// Start server on port 8080
const port = 5000;
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});

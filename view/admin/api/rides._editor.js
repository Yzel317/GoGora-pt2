//Author: Justine Lucas
const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');

const app = express();
const port = 3000;

// Middleware
app.use(bodyParser.json());

// Database connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'your_db_username',
  password: 'your_db_password',
  database: 'your_database_name',
});

db.connect((err) => {
  if (err) {
    console.error('Database connection failed:', err.stack);
    return;
  }
  console.log('Connected to database');
});

// API Endpoints

// GET all rides
app.get('/api/rides', (req, res) => {
  db.query('SELECT * FROM rides', (err, results) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    res.json(results);
  });
});

// POST a new ride
app.post('/api/rides', (req, res) => {
  const { plate_number, type_of_ride, seating_capacity, route, schedule } = req.body;
  const query = 'INSERT INTO rides (plate_number, driver, seats_available, route, departure) VALUES (?, ?, ?, ?, ?)';
  db.query(query, [plate_number, type_of_ride, seating_capacity, route, schedule], (err, result) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    res.json({ success: true, insertId: result.insertId });
  });
});

// PUT (update) a ride
app.put('/api/rides/:id', (req, res) => {
  const rideId = req.params.id;
  const { plate_number, type_of_ride, seating_capacity, route, schedule } = req.body;
  const query = 'UPDATE rides SET plate_number = ?, driver = ?, seats_available = ?, route = ?, departure = ? WHERE ride_id = ?';
  db.query(query, [plate_number, type_of_ride, seating_capacity, route, schedule, rideId], (err, result) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    res.json({ success: true, affectedRows: result.affectedRows });
  });
});

// DELETE a ride
app.delete('/api/rides/:id', (req, res) => {
  const rideId = req.params.id;
  const query = 'DELETE FROM rides WHERE ride_id = ?';
  db.query(query, [rideId], (err, result) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    res.json({ success: true, affectedRows: result.affectedRows });
  });
});

// Start the server
app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
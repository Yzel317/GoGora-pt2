// view/admin/api/rides.js
const express = require('express');
const router = express.Router();
const mysql = require('mysql2/promise');

// Create the connection pool
const pool = mysql.createPool({
  host: 'localhost',
  user: 'root',  // your MySQL username
  password: '',  // your MySQL password
  database: 'gogora_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Route to fetch all rides
router.get('/', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT * FROM rides');
    if (rows.length === 0) {
      return res.status(404).json({ error: 'No rides found' });
    }
    res.json(rows);
  } catch (error) {
    console.error('Database error:', error);
    res.status(500).json({ error: 'Failed to fetch rides' });
  }
});

// Add this PUT route to handle updates
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const { plate_number, route, departure, seats_available } = req.body;
    
    console.log('Received update request for ride:', id);
    console.log('Update data:', req.body);
    
    const updateQuery = 'UPDATE rides SET plate_number = ?, route = ?, departure = ?, seats_available = ? WHERE ride_id = ?';
    const values = [plate_number, route, departure, seats_available, id];
    
    console.log('Executing query:', updateQuery);
    console.log('With values:', values);

    const [result] = await pool.query(updateQuery, values);
    console.log('Update result:', result);

    if (result.affectedRows === 0) {
      console.log('No ride found with ID:', id);
      return res.status(404).json({ error: 'Ride not found' });
    }

    // Fetch the updated ride
    const [updatedRide] = await pool.query('SELECT * FROM rides WHERE ride_id = ?', [id]);
    console.log('Updated ride data:', updatedRide[0]);
    
    res.json(updatedRide[0]);
  } catch (error) {
    console.error('Database error:', error);
    res.status(500).json({ error: 'Failed to update ride: ' + error.message });
  }
});

module.exports = router;

// view/admin/api/rides._editor.js
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

// Route to add new ride
router.post('/add', async (req, res) => {
  try {
    const {
      plate_number,
      route,
      departure,
      seats_available,
      ride_type,
      capacity,
      queue,
      time
    } = req.body;

    const [result] = await pool.query(
      'INSERT INTO rides (plate_number, route, time, seats_available, ride_type, departure, capacity, queue) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
      [plate_number, route, time, seats_available, ride_type, departure, capacity, queue]
    );

    res.json({
      success: true,
      ride_id: result.insertId,
      message: 'Ride added successfully'
    });
  } catch (error) {
    console.error('Error in /api/rides/add:', error);
    res.status(500).json({
      success: false,
      message: error.message || 'Failed to add ride'
    });
  }
});

// Route to update ride
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

// Frontend JavaScript functions
async function addNewRide(rideData) {
  try {
    const response = await fetch('/api/rides/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(rideData)
    });

    if (!response.ok) {
      throw new Error('Failed to add ride');
    }

    return await response.json();
  } catch (error) {
    console.error('Error adding ride:', error);
    throw error;
  }
}

// Add form submission handler
document.addEventListener('DOMContentLoaded', () => {
  const addRideForm = document.getElementById('addRideForm');
  if (addRideForm) {
    addRideForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const newRide = {
        plate_number: document.getElementById('addPlateNumber').value,
        route: document.getElementById('addRoute').value,
        departure: document.getElementById('addDeparture').value,
        seats_available: parseInt(document.getElementById('addSeatsAvailable').value),
        ride_type: document.getElementById('addRideType').value,
        capacity: parseInt(document.getElementById('addSeatsAvailable').value),
        queue: 1,
        time: new Date().toISOString()
      };

      try {
        const result = await addNewRide(newRide);
        if (result.success) {
          console.log('Ride added successfully:', result);
          alert('Ride added successfully!');
          closeAddModal();
          await fetchData(); // Refresh the ride list
        } else {
          throw new Error(result.message || 'Failed to add ride');
        }
      } catch (error) {
        console.error('Error adding ride:', error);
        alert('Failed to add ride: ' + error.message);
      }
    });
  }
});

module.exports = router;

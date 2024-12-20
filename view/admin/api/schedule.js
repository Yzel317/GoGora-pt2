// view/admin/api/schedule.js
const express = require('express');
const router = express.Router();
const db = require('./db'); // Import the db connection

// Route to fetch all rides
router.get('/', async (req, res) => {
  try {
    // Query to fetch all rides
    const [rows] = await db.query('SELECT * FROM schedule');
    if (rows.length === 0) {
      return res.status(404).json({ error: 'No Schedule Found' });
    }
    res.json(rows); // Send rides data as JSON
  } catch (error) {
    console.error('Database error:', error); // Log any errors
    res.status(500).json({ error: 'Failed to fetch rides' }); // Handle errors
  }
});

// Route to add a new schedule
router.post('/', async (req, res) => {
  const { route, departure, seats_available } = req.body;

  // Validate the incoming data
  if  (!route || !departure || !seats_available) {
    return res.status(400).json({ error: 'Route, Departure, and Seats Available are required' });
  }

  try {
    // Query to insert a new schedule into the database
    const query = 'INSERT INTO schedule ( route, departure, seats_available) VALUES ( ?, ?, ?)';
    const [result] = await db.query(query, [route, departure, seats_available]);

    console.log(result);
    
    // Send a success response with the inserted schedule ID
    res.status(201).json({ success: true, insertId: result.insertId });
  } catch (error) {
    console.error('Database error:', error); // Log any errors
    res.status(500).json({ error: 'Failed to add schedule' }); // Handle database errors
  }
});

router.delete('/:sched_id', async (req, res) => {
  const { sched_id } = req.params;

  if (!sched_id) {
    return res.status(400).json({ error: 'sched_id is required' });
  }

  // Delete the ride from the database
  try {
    const result = await db.query('DELETE FROM schedule WHERE sched_id = ?', [sched_id]);
    res.json({ success: true, message: 'Ride deleted successfully' });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Failed to delete ride' });
  }
});
//asdf
module.exports = router;

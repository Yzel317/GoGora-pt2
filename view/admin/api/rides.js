// view/admin/api/rides.js
const express = require('express');
const router = express.Router();
const db = require('./db'); // Import the db connection

// Route to fetch all rides
router.get('/', async (req, res) => {
  try {
    // Query to fetch all rides
    const [rows] = await db.query('SELECT * FROM rides');
    if (rows.length === 0) {
      return res.status(404).json({ error: 'No rides found' });
    }
    res.json(rows); // Send rides data as JSON
  } catch (error) {
    console.error('Database error:', error); // Log any errors
    res.status(500).json({ error: 'Failed to fetch rides' }); // Handle errors
  }
});

module.exports = router;

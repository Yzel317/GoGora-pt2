const express = require('express');
const router = express.Router();
const db = require('./db'); 
// Define the GET route to fetch rides
router.get('/', async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM rides'); // Database query for fetching rides
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'No rides found' });
    }
    res.json(result.rows); // Send the rides data back as JSON
  } catch (error) {
    console.error('Database error:', error); // Log error for debugging
    res.status(500).json({ error: 'Failed to fetch rides' }); // Handle any DB errors
  }
});

module.exports = router;

// Author: Justine Lucas
// routes/stats.js
const express = require('express');
const router = express.Router();
const db = require('../db');

// Get ride statistics
router.get('/', async (req, res) => {
  try {
    const result = await db.query('SELECT ride_id, time_of_arrival, time_of_departure FROM rides');
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Failed to fetch statistics' });
  }
});

// Example Stats Routes
router.get('/download', (req, res) => {
    res.json({ message: 'Downloading statistical data' });
});

module.exports = router;
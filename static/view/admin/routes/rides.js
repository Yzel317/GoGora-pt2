// Author: Justine Lucas
const express = require('express');
const router = express.Router();
const db = require('../db');

// Get all rides
router.get('/', async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM rides');
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Failed to fetch rides' });
  }
});

// Add a new ride
router.post('/', async (req, res) => {
  const { plate_number, type_of_ride, seating_capacity, route, schedule } = req.body;
  try {
    await db.query('INSERT INTO rides (plate_number, type_of_ride, seating_capacity, route, schedule) VALUES ($1, $2, $3, $4, $5)', 
    [plate_number, type_of_ride, seating_capacity, route, schedule]);
    res.json({ message: 'Ride added' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to add ride' });
  }
});

module.exports = router;

router.post('/', (req, res) => {
    res.json({ message: 'Creating a new ride' });
});

router.put('/:id', (req, res) => {
    res.json({ message: `Updating ride with ID ${req.params.id}` });
});

router.delete('/:id', (req, res) => {
    res.json({ message: `Deleting ride with ID ${req.params.id}` });
});

module.exports = router;
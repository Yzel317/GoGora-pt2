// Author: Justine Lucas
// routes/blacklist.js
const express = require('express');
const router = express.Router();
const db = require('../db'); // Assuming your database connection

// Get all blacklisted users
router.get('/', async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM blacklist');
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Failed to fetch blacklist data' });
  }
});

// Approve or reject blacklisting
router.put('/:id', async (req, res) => {
  const { id } = req.params;
  const { status } = req.body; // status can be 'approved' or 'rejected'

  try {
    const result = await db.query('UPDATE blacklist SET blacklist_status = $1 WHERE blacklist_id = $2', [status, id]);
    res.json({ message: 'Blacklist status updated' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to update blacklist status' });
  }
});

module.exports = router;

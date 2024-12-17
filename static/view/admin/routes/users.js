// Author: Justine Lucas
// routes/users.js
const express = require('express');
const router = express.Router();
const db = require('../db');

// Get all users
router.get('/', async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM users');
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Failed to fetch users' });
  }
});

// Add a new user
router.post('/', async (req, res) => {
  const { username, firstname, lastname, password, email, role, user_type, avatar } = req.body;
  try {
    await db.query('INSERT INTO users (username, firstname, lastname, password, email, role, user_type, avatar) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)', [username, firstname, lastname, password, email, role, user_type, avatar]);
    res.json({ message: 'User added' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to add user' });
  }
});

// Delete a user
router.delete('/:id', async (req, res) => {
  const { id } = req.params;
  try {
    await db.query('DELETE FROM users WHERE user_id = $1', [id]);
    res.json({ message: 'User deleted' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to delete user' });
  }
});

module.exports = router;
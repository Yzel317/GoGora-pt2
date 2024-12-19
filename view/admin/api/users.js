// Authors: Justine Lucas, Chryzel Beray

const express = require('express');
const router = express.Router();
const db = require('./db');
const bcrypt = require('bcrypt');

// Get all users
router.get('/', async (req, res) => {
  try {
    const users = await db.query('SELECT * FROM users'); 
    res.json(users[0]);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching users' });
  }
});

// Add a new user
router.post('/', async (req, res) => {
  const { username, firstName, lastName, email, role, password } = req.body;

  try {
    // Hash the password
    const hashedPassword = await bcrypt.hash(password, 10); // Salt rounds: 10

    // Insert user into the database
    const result = await db.query(
      'INSERT INTO users (username, firstname, lastname, email, role, password) VALUES (?, ?, ?, ?, ?, ?)',
      [username, firstName, lastName, email, role, hashedPassword]
    );

    res.status(201).json({ message: 'User added successfully' });
  } catch (error) {
    console.error('Error adding user:', error);
    res.status(500).json({ error: 'Failed to add user' });
  }
});

// Update a user
router.put('/:id', async (req, res) => {
  const { id } = req.params;
  const { username, firstname, lastname, email, role } = req.body;

  try {
    const result = await db.query(
      `UPDATE users 
       SET username = ?, firstname = ?, lastname = ?, email = ?, role = ? 
       WHERE user_id = ?`,
      [username, firstname, lastname, email, role, id]
    );

    if (result.affectedRows > 0) {
      res.json({ message: 'User updated successfully' });
    } else {
      res.status(404).json({ message: 'User not found' });
    }
  } catch (error) {
    res.status(500).json({ message: 'Failed to update user', error });
  }
});

// Delete a user
router.delete('/:id', async (req, res) => {
  const { id } = req.params;
  try {
    await db.query('DELETE FROM users WHERE user_id = ?', [id]);
    res.json({ message: 'User deleted' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to delete user' });
  }
});

module.exports = router;
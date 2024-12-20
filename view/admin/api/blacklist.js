// Author: Justine Lucas
// routes/blacklist.js
const express = require('express');
const router = express.Router();
const db = require('./db');

// Get all blacklisted users
router.get('/', async (req, res) => {
    try {
        const query = `
            SELECT b.*, u.username, u.firstname, u.lastname 
            FROM blacklist b
            JOIN users u ON b.user_id = u.user_id
            ORDER BY b.created_at DESC
        `;
        const blacklist = await db.query(query);
        res.json(blacklist[0]);
    } catch (error) {
        res.status(500).json({ message: 'Error fetching blacklist' });
    }
});

// Add user to blacklist
router.post('/', async (req, res) => {
    const { user_id, reason_id, notes } = req.body;
    
    try {
        const query = `
            INSERT INTO blacklist (user_id, reason_id, notes, status, created_at)
            VALUES (?, ?, ?, 'pending', NOW())
        `;
        await db.query(query, [user_id, reason_id, notes]);
        res.status(201).json({ message: 'User added to blacklist' });
    } catch (error) {
        res.status(500).json({ message: 'Error adding to blacklist' });
    }
});

// Update blacklist status
router.put('/:id', async (req, res) => {
    const { id } = req.params;
    const { status, notes } = req.body;
    
    try {
        const query = `
            UPDATE blacklist 
            SET status = ?, 
                notes = COALESCE(?, notes),
                updated_at = NOW()
            WHERE blacklist_id = ?
        `;
        await db.query(query, [status, notes, id]);
        res.json({ message: 'Blacklist status updated' });
    } catch (error) {
        res.status(500).json({ message: 'Error updating blacklist status' });
    }
});

// Delete from blacklist
router.delete('/:id', async (req, res) => {
    const { id } = req.params;
    
    try {
        await db.query('DELETE FROM blacklist WHERE blacklist_id = ?', [id]);
        res.json({ message: 'Removed from blacklist' });
    } catch (error) {
        res.status(500).json({ message: 'Error removing from blacklist' });
    }
});

module.exports = router;

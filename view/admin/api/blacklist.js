// Author: Justine Lucas
// routes/blacklist.js
const express = require('express');
const router = express.Router();
const db = require('./db');

// Get all blacklisted users
router.get('/', async (req, res) => {
    try {
        const query = `
            SELECT * FROM blacklist
            ORDER BY blacklist_date DESC
        `;
        const blacklist = await db.query(query);
        res.json(blacklist[0]);
    } catch (error) {
        res.status(500).json({ message: 'Error fetching blacklist' });
    }
});

// Add user to blacklist
router.post('/', async (req, res) => {
    const { username, reason } = req.body;
    
    try {
        const query = `
            INSERT INTO blacklist (username, blacklist_date, blacklist_status, reason)
            VALUES (?, NOW(), 'pending', ?)
        `;
        await db.query(query, [username, reason]);
        res.status(201).json({ message: 'User added to blacklist' });
    } catch (error) {
        res.status(500).json({ message: 'Error adding to blacklist' });
    }
});

// Update blacklist status
router.put('/:id', async (req, res) => {
    const { id } = req.params;
    const { blacklist_status, reason } = req.body;
    
    try {
        const query = `
            UPDATE blacklist 
            SET blacklist_status = ?,
                reason = COALESCE(?, reason)
            WHERE blacklist_id = ?
        `;
        await db.query(query, [blacklist_status, reason, id]);
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

const express = require('express');
const router = express.Router();
const db = require('./db');

// Get all schedules
router.get('/schedules', async (req, res) => {
  try {
    const [schedules] = await db.execute('SELECT * FROM schedules');
    res.json(schedules);
  } catch (error) {
    console.error('Error fetching schedules:', error);
    res.status(500).json({ error: 'Failed to fetch schedules' });
  }
});

// Add new schedule
router.post('/schedules', async (req, res) => {
  try {
    const { ride_id, route, departure, seats_available } = req.body;
    
    const [result] = await db.execute(
      'INSERT INTO schedules (ride_id, route, departure, seats_available) VALUES (?, ?, ?, ?)',
      [ride_id, route, departure, seats_available]
    );

    res.json({
      success: true,
      sched_id: result.insertId,
      message: 'Schedule added successfully'
    });
  } catch (error) {
    console.error('Error adding schedule:', error);
    res.status(500).json({
      success: false,
      message: error.message || 'Failed to add schedule'
    });
  }
});

// Update schedule
router.put('/schedules/:id', async (req, res) => {
  try {
    const { route, departure, seats_available } = req.body;
    const schedId = req.params.id;

    const [result] = await db.execute(
      'UPDATE schedules SET route = ?, departure = ?, seats_available = ? WHERE sched_id = ?',
      [route, departure, seats_available, schedId]
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Schedule not found' });
    }

    res.json({
      success: true,
      message: 'Schedule updated successfully'
    });
  } catch (error) {
    console.error('Error updating schedule:', error);
    res.status(500).json({
      success: false,
      message: error.message || 'Failed to update schedule'
    });
  }
});

// Delete schedule
router.delete('/schedules/:id', async (req, res) => {
  try {
    const schedId = req.params.id;
    const [result] = await db.execute('DELETE FROM schedules WHERE sched_id = ?', [schedId]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Schedule not found' });
    }

    res.json({
      success: true,
      message: 'Schedule deleted successfully'
    });
  } catch (error) {
    console.error('Error deleting schedule:', error);
    res.status(500).json({
      success: false,
      message: error.message || 'Failed to delete schedule'
    });
  }
});

module.exports = router; 
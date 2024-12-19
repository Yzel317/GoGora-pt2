// Author: Justine Lucas
const express = require('express');
const app = express();
const router = express.Router();
const db = require('./db');
const multer = require('multer');
const path = require('path');
const { createObjectCsvWriter } = require('csv-writer');

app.use(express.json());

const PORT = 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));

// Configure storage for uploaded images
const storage = multer.diskStorage({
  destination: './uploads/',
  filename: (req, file, cb) => {
    cb(null, `${Date.now()}-${file.originalname}`);
  },
});

const upload = multer({ storage });

// Get a specific ride by ID
router.get('/:id', async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM rides WHERE ride_id = $1', [req.params.id]);
    if (result.rows.length === 0) return res.status(404).json({ error: 'Ride not found' });
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: 'Failed to fetch ride details' });
  }
});

// Update a ride with image upload
router.put('/:id', upload.single('image'), async (req, res) => {
  const { plate_number, ride_type, route, departure, capacity } = req.body;
  const image = req.file ? req.file.filename : null;

  try {
    const fields = [];
    const values = [req.params.id];

    if (plate_number) { fields.push(`plate_number = $${values.length + 1}`); values.push(plate_number); }
    if (route) { fields.push(`route = $${values.length + 1}`); values.push(route); }
    if (departure) { fields.push(`departure = $${values.length + 1}`); values.push(departure); }
    if (capacity) { fields.push(`capacity = $${values.length + 1}`); values.push(capacity); }
    if (ride_type) { fields.push(`ride_type = $${values.length + 1}`); values.push(ride_type); }
    if (image) { fields.push(`image = $${values.length + 1}`); values.push(image); }

    const query = `UPDATE rides SET ${fields.join(', ')} WHERE ride_id = $1`;
    await db.query(query, values);

    res.json({ message: 'Ride updated successfully' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to update ride' });
  }
});

// Delete a ride
router.delete('/:id', async (req, res) => {
  try {
    await db.query('DELETE FROM rides WHERE ride_id = $1', [req.params.id]);
    res.json({ message: 'Ride deleted successfully' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to delete ride' });
  }
});

module.exports = router;


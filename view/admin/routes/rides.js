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

// Get all rides with search and filter capabilities
router.get('/', async (req, res) => {
  const { search, type } = req.query; // Example query: ?search=abc&type=Jeepney
  let query = 'SELECT * FROM rides';
  let conditions = [];
  let values = [];

  if (search) {
    conditions.push("(plate_number LIKE $1 OR route LIKE $1)");
    values.push(`%${search}%`);
  }
  if (type) {
    conditions.push("ride_type = $2");
    values.push(type);
  }

  if (conditions.length) {
    query += ' WHERE ' + conditions.join(' AND ');
  }

  try {
    const result = await db.query(query, values);
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Failed to fetch rides' });
  }
});

// Add a new ride with image upload
router.post('/', upload.single('image'), async (req, res) => {
  const { plate_number, ride_type, route, departure, capacity } = req.body;
  const image = req.file ? req.file.filename : null;

  try {
    await db.query(
      'INSERT INTO rides (plate_number, route, departure, capacity, ride_type, image) VALUES ($1, $2, $3, $4, $5, $6)',
      [plate_number, route, departure, capacity, ride_type, image]
    );
    res.json({ message: 'Ride added successfully' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to add ride' });
  }
});

// Update ride with image upload
router.put('/:id', upload.single('image'), async (req, res) => {
  const { plate_number, route, departure, capacity, ride_type } = req.body;
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

// Export all ride profiles as CSV
router.get('/export/rides', async (req, res) => {
  try {
    const result = await db.query('SELECT * FROM rides');
    const csvWriter = createObjectCsvWriter({
      path: './exports/rides.csv',
      header: [
        { id: 'ride_id', title: 'Ride ID' },
        { id: 'plate_number', title: 'Plate Number' },
        { id: 'route', title: 'Route' },
        { id: 'departure', title: 'Departure' },
        { id: 'capacity', title: 'Capacity' },
        { id: 'ride_type', title: 'Type' },
        { id: 'image', title: 'Image' },
      ],
    });

    await csvWriter.writeRecords(result.rows);
    res.download('./exports/rides.csv');
  } catch (error) {
    res.status(500).json({ error: 'Failed to export rides' });
  }
});

module.exports = router;

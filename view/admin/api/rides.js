// view/admin/api/rides.js
const express = require('express');
const router = express.Router();
const db = require('./db'); // Import the db connection
const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Configure multer for file uploads
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    const uploadPath = path.join(__dirname, '../../../model/uploads');
    // Create directory if it doesn't exist
    if (!fs.existsSync(uploadPath)) {
      fs.mkdirSync(uploadPath, { recursive: true });
    }
    cb(null, uploadPath);
  },
  filename: function (req, file, cb) {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, `${req.body.plate_number}-${uniqueSuffix}${path.extname(file.originalname)}`);
  }
});

const upload = multer({ 
  storage: storage,
  limits: { fileSize: 6 * 1024 * 1024 }, // 6MB limit
  fileFilter: (req, file, cb) => {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (allowedTypes.includes(file.mimetype)) {
      cb(null, true);
    } else {
      cb(new Error('Invalid file type. Only JPEG, JPG and PNG files are allowed.'));
    }
  }
});

// Route to fetch all rides
router.get('/', async (req, res) => {
  try {
    // Query to fetch all rides
    const [rows] = await db.query('SELECT * FROM rides');
    if (rows.length === 0) {
      return res.status(404).json({ error: 'No rides found' });
    }
    res.json(rows); // Send rides data as JSON
  } catch (error) {
    console.error('Database error:', error); // Log any errors
    res.status(500).json({ error: 'Failed to fetch rides' }); // Handle errors
  }
});

// Add image upload endpoint
router.post('/upload-image', upload.single('image'), async (req, res) => {
  try {
    const { ride_id } = req.body;
    const imagePath = req.file ? req.file.path.replace(/\\/g, '/') : null;
    
    // Read the file as binary data
    const imageBlob = req.file ? fs.readFileSync(req.file.path) : null;
    
    // Update the database with both the file path and blob
    const query = 'UPDATE rides SET image_path = ?, image_blob = ? WHERE ride_id = ?';
    await db.execute(query, [imagePath, imageBlob, ride_id]);
    
    res.json({
      success: true,
      imagePath: imagePath
    });
  } catch (error) {
    console.error('Error uploading image:', error);
    res.status(500).json({
      success: false,
      message: 'Failed to upload image'
    });
  }
});

module.exports = router;

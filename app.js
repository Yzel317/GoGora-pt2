const express = require('express');
const path = require('path');
const app = express();
const multer = require('multer');
const fs = require('fs');
const cors = require('cors');

// Routes
const ridesRouter = require('./view/admin/api/ride_editor');
const scheduleRouter = require('./view/admin/api/schedule');
const usersRouter = require('./view/admin/api/users');
const blacklistRouter = require('./view/admin/api/blacklist');

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static files from the 'view/admin' directory for HTML, CSS, and JS
// This will serve index.html, admin.css, etc. from root path
app.use(express.static(path.join(__dirname, 'view/admin'))); // Serve index.html, admin.css, etc.

// Serve static files from the 'view/admin/api' directory for API JS
app.use('/js', express.static(path.join(__dirname, 'view/admin/api'))); // Serve script.js

// Serve static files (e.g., uploaded images)
app.use('/model/uploads', express.static(path.join(__dirname, 'uploads')));

// Serve static files from the 'static' directory
app.use('/static', express.static('static'));

// Configure multer for file upload
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    const uploadDir = path.join(__dirname, 'model/uploads');
    // Create directory if it doesn't exist
    if (!fs.existsSync(uploadDir)) {
      fs.mkdirSync(uploadDir, { recursive: true });
    }
    cb(null, uploadDir);
  },
  filename: function (req, file, cb) {
    // Get file extension
    const ext = path.extname(file.originalname);
    // Create filename using ride_id and route
    const filename = `${req.body.ride_id}_${req.body.route}${ext}`;
    cb(null, filename);
  }
});

const upload = multer({
  storage: storage,
  limits: {
    fileSize: 6 * 1024 * 1024 // 6MB limit
  },
  fileFilter: function (req, file, cb) {
    // Accept only jpeg, jpg, png
    if (file.mimetype === "image/jpeg" || 
        file.mimetype === "image/jpg" || 
        file.mimetype === "image/png") {
      cb(null, true);
    } else {
      cb(new Error('Only .jpeg, .jpg and .png files are allowed!'), false);
    }
  }
});

// Routes setup
app.use('/api/rides', ridesRouter);
app.use('/api/schedule', scheduleRouter);
app.use('/api/users', usersRouter);
app.use('/api/blacklist', blacklistRouter);

// Add CORS middleware
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
  res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  if (req.method === 'OPTIONS') {
    return res.sendStatus(200);
  }
  next();
});

// Make sure this route is defined BEFORE your 404 handler
app.post('/api/rides/update-image', upload.single('image'), async (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ error: 'No file uploaded' });
    }

    // Just send success response without database update
    res.json({ 
      success: true, 
      message: 'Image updated ! ✅',
      // Use the default jeep image path
      path: '/assets/jeep.jpg'
    });

  } catch (error) {
    console.error('Error handling image:', error);
    res.status(500).json({ 
      error: 'Failed to handle image',
      details: error.message 
    });
  }
});

// Update the rides endpoint
app.get('/api/rides', (req, res) => {
  try {
    // Send the sample data
    const sampleRides = [
      {
        ride_id: 1,
        plate_number: "ABC123",
        route: "Bakakeng - Main",
        departure: "2024-12-19 01:40:38",
        seats_available: 23
      },
      {
        ride_id: 2,
        plate_number: "XYZ789",
        route: "Military Cut-off - Main",
        departure: "2024-12-19 02:30:00",
        seats_available: 15
      }
    ];
    res.json(sampleRides);
  } catch (error) {
    console.error('Error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Default route for catching any unhandled requests
app.use((req, res) => {
  res.status(404).json({ error: "Route not found" });
});

// Start server on port 8080
const port = 5000;
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});

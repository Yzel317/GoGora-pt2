// File: server.js
const express = require('express');
const bodyParser = require('body-parser');
const path = require('path');
const config = require('../config');
const app = express();

// Import routes
const authRoutes = require('./routes/auth');
const userRoutes = require('./routes/users');
const rideRoutes = require('./routes/rides');
const blacklistRoutes = require('./routes/blacklist');
const statsRoutes = require('./routes/stats');

// Middleware
app.use(bodyParser.json());
app.use(express.static(path.join(__dirname, 'static')));

// API Endpoints
app.use('/api/auth', authRoutes);
app.use('/api/users', userRoutes);
app.use('/api/rides', rideRoutes);
app.use('/api/blacklist', blacklistRoutes);
app.use('/api/stats', statsRoutes);

// Serve admin UI
app.get('/admin', (req, res) => {
    res.sendFile(path.join(__dirname, 'view/admin/admin_dashboard.html'));
});

// Serve login page
app.get('/login', (req, res) => {
    res.sendFile(path.join(__dirname, 'view/admin/index.html'));
});

// Start server
app.listen(config.port, () => {
    console.log(`Server is running at http://localhost:${config.port}`);
});

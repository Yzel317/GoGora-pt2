const express = require('express');
const app = express();
const path = require('path');

// Serve static files for script.js
app.use('/js', express.static(path.join(__dirname, 'view', 'admin', 'routes')));

// Serve uploads folder for images
app.use('/model/uploads', express.static(path.join(__dirname, 'uploads')));

// Serve exports folder for CSV files
app.use('/model/exports', express.static(path.join(__dirname, 'exports')));

// Update to use routes from 'view/admin/routes'
const ridesRouter = require(path.join(__dirname, 'view', 'admin', 'routes', 'rides'));
app.use('view/admin/api/rides', ridesRouter);  // The routes will now be available under /api/rides

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.listen(8080, () => console.log('Server started on port 8080'));

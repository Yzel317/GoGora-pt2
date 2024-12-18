const express = require('express');
const app = express();
const path = require('path');

// Update to use routes from 'view/admin/routes'
const ridesRouter = require(path.join(__dirname, 'view', 'admin', 'routes', 'rides'));

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve uploads folder for images
app.use('/model/uploads', express.static(path.join(__dirname, 'uploads')));

// Serve exports folder for CSV files
app.use('/model/exports', express.static(path.join(__dirname, 'exports')));

// Define the route for the rides API
app.use('/api/rides', ridesRouter);  // The routes will now be available under /api/rides

app.listen(3000, () => console.log('Server started on port 3000'));
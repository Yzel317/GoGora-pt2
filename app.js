const express = require('express');
const path = require('path');
const app = express();

// Routes
const ridesRouter = require('./view/admin/api/rides');  // Ensure the path is correct
const scheduleRouter = require('./view/admin/api/schedule');

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static files from the 'view/admin' directory for HTML, CSS, and JS
// This will serve index.html, admin.css, etc. from root path
app.use(express.static(path.join(__dirname, 'view/admin'))); // Serve index.html, admin.css, etc.

// Serve static files from the 'view/admin/api' directory for API JS
app.use('/api', express.static(path.join(__dirname, 'view/admin/api'))); // Serve script.js

// Serve static files (e.g., uploaded images)
app.use('/model/uploads', express.static(path.join(__dirname, 'uploads')));

// Routes setup
app.use('/api/rides', ridesRouter);

// schedule setup
app.use('/api/schedule', scheduleRouter);

// Default route for catching any unhandled requests
app.use((req, res) => {
  res.status(404).send("Route not found");
});

// Start server on port 8080
const port = 5000;
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});

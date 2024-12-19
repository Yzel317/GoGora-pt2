const express = require('express');
const path = require('path');
const app = express();

// Routes
const ridesRouter = require('./view/admin/api/rides');  

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(express.static(path.join(__dirname, 'view/admin')));  // Serves static files from view/admin

app.use('/api', express.static(path.join(__dirname, 'view/admin/api')));  // Serves files from view/admin/api

// Serve static files (e.g., uploaded images)
app.use('/model/uploads', express.static(path.join(__dirname, 'uploads')));

// Routes setup for rides API
app.use('/api/rides', ridesRouter);

// Default route for catching any unhandled requests
app.use((req, res) => {
  res.status(404).send("Route not found");
});

// Start server on port 8080
const port = 8080;
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});


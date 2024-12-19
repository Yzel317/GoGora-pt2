const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');

const app = express();
const port = 3000;

app.use(bodyParser.json());

// Database connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'gogora_db',
});

db.connect((err) => {
  if (err) {
    console.error('Database connection failed:', err.stack);
    return;
  }
  console.log('Connected to database');
});

app.get('/api/schedule', (req, res) => {
    db.query('SELECT * FROM schedule', (err, results) => {
      if (err) {
        return res.status(500).json({ error: err.message });
      }
      res.json(results);
    });
  });
  
  // POST a new ride
  app.post('/api/schedule', (req, res) => {
    const { route, departure, seats_available } = req.body;
    const query = 'INSERT INTO schedule (route, departure, seats_available) VALUES (?, ?, ?)';
    db.query(query, [route, departure, seats_available], (err, result) => {
      if (err) {
        return res.status(500).json({ error: err.message });
      }
      res.json({ success: true, insertId: result.insertId });
    });
  });
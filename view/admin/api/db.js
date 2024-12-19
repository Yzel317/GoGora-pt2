// view/admin/api/db.js
const mysql = require('mysql2');

// Create a connection pool
const pool = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '', // Use your MySQL password if you have one
  database: 'gogora_db', // Your database name
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Export the promise-based pool for easier async/await usage
const db = pool.promise();

module.exports = db;

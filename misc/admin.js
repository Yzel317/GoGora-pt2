const mysql = require('mysql2/promise');
const bcrypt = require('bcrypt');

// Create a database connection pool (adjust these settings as per your XAMPP configuration)
const pool = mysql.createPool({
    host: 'localhost',
    user: 'root', // Default XAMPP username
    password: '', // Default XAMPP password (empty by default)
    database: 'your_database', // Replace with your database name
});

// Handle login
exports.login = async (req, res) => {
    const { username, password } = req.body;

    try {
        // Query the database securely with a prepared statement
        const query = 'SELECT * FROM users WHERE username = ?';
        const [rows] = await pool.execute(query, [username]);

        if (rows.length > 0) {
            const user = rows[0];

            // Compare the hashed password
            const passwordMatch = await bcrypt.compare(password, user.password);

            if (passwordMatch) {
                // Check if the user's role is "Admin"
                if (user.role === 'Admin') {
                    res.status(200).json({ message: 'Login successful', user });
                } else {
                    res.status(403).json({ message: 'Access denied. User is not an Admin.' });
                }
            } else {
                res.status(401).json({ message: 'Invalid credentials' });
            }
        } else {
            res.status(401).json({ message: 'Invalid credentials' });
        }
    } catch (error) {
        console.error('Error during login:', error);
        res.status(500).json({ message: 'Internal server error' });
    }
};

// Handle logout
exports.logout = (req, res) => {
    res.status(200).json({ message: 'Logout successful' });
};

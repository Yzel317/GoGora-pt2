// Author: Justine Lucas
// Handle user login and set session
exports.login = async (req, res) => {
    const { username, password } = req.body;

    try {
        // Authenticate user
        const [user] = await db.query('SELECT * FROM users WHERE username = ? AND password = ?', [username, password]);

        if (user.length > 0) {
            // Create session for authenticated user
            req.session.username = username;
            res.redirect('/admin/dashboard');
        } else {
            res.status(401).send('Invalid username or password');
        }
    } catch (error) {
        console.error('Error during login:', error);
        res.status(500).send('Server error');
    }
};

// Handle logout
exports.logout = (req, res) => {
    req.session.destroy(() => {
        res.redirect('/login');
    });
};

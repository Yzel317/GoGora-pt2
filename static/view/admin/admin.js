// Handle login
exports.login = (req, res) => {
    const { username, password } = req.body;
    const user = users.find(u => u.username === username && u.password === password);
    if (user) {
        // Simulate session or token (replace with JWT or session in production)
        res.status(200).json({ message: 'Login successful', user });
    } else {
        res.status(401).json({ message: 'Invalid credentials' });
    }
};

// Handle logout
exports.logout = (req, res) => {
    // Simulate clearing session or token
    res.status(200).json({ message: 'Logout successful' });
};
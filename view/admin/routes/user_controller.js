// Author: Justine Lucas
const users = []; // Temporary in-memory storage

exports.getAllUsers = (req, res) => {
    res.json(users);
};

exports.createUser = (req, res) => {
    const user = req.body;
    user.id = users.length + 1;
    users.push(user);
    res.status(201).json(user);
};

exports.updateUser = (req, res) => {
    const id = parseInt(req.params.id);
    const user = users.find(u => u.id === id);
    if (user) {
        Object.assign(user, req.body);
        res.json(user);
    } else {
        res.status(404).send('User not found');
    }
};

exports.deleteUser = (req, res) => {
    const id = parseInt(req.params.id);
    const index = users.findIndex(u => u.id === id);
    if (index !== -1) {
        users.splice(index, 1);
        res.status(204).send();
    } else {
        res.status(404).send('User not found');
    }
};
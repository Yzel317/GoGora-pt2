//Author: Chryzel Beray
let users = [];
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    initializeFilters();
    
    // Add event listeners for real-time filtering
    document.getElementById('searchInput').addEventListener('input', filterUsers);
    document.getElementById('filterRole').addEventListener('change', filterUsers);
    document.getElementById('filterUserType').addEventListener('change', filterUsers);

    // Add event listeners for modals
    document.querySelector('.add-schedule')?.addEventListener('click', openAddUserModal);
    document.getElementById('addUserForm')?.addEventListener('submit', addUser);
    document.getElementById('updateUserForm')?.addEventListener('submit', updateUser);
    
    // Add event listeners for closing modals
    document.getElementById('closeModalButton')?.addEventListener('click', closeAddUserModal);
    document.getElementById('closeUpdateModalButton')?.addEventListener('click', closeUpdateUserModal);
});

// Load users from the API and populate the table
async function loadUsers() {
    const userTableBody = document.getElementById('userTableBody');
    userTableBody.innerHTML = '<tr><td colspan="8">Loading...</td></tr>';
    try {
        const response = await fetch('/api/users');
        users = await response.json(); // Store users globally
        
        // Clear existing table rows
        userTableBody.innerHTML = '';
    
        users.forEach(user => {
            const avatar = user.avatar ? `<img src="${user.avatar}" alt="${user.username}'s avatar">` : 'N/A'; // Check if avatar exists
    
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.username}</td>
                <td>${user.firstname}</td>
                <td>${user.lastname}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>${user.password}</td>
                <td>${avatar}</td>  <!-- Display the image or N/A if not available -->
                <td>
                    <button class="edit-button" onclick="openUpdateModal(${user.user_id})">Edit</button>
                    <button class="delete-button" onclick="deleteUser(${user.user_id})">Delete</button>
                </td>
            `;
            userTableBody.appendChild(row);
        });
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

// Add user to the database
async function addUser(event) {
    event.preventDefault();
  
    const username = document.getElementById('username').value;
    const firstname = document.getElementById('firstName').value;
    const lastname = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const role = document.getElementById('role').value;
  
    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }
  
    const newUser = {
        username,
        firstname,
        lastname,
        email,
        role,
        avatar: null, // Or a default avatar path
    };
  
    // Update `users` array and UI immediately
    users.push(newUser);
    loadUsers(users); // Re-render the table
  
    try {
        const response = await fetch('/api/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ...newUser, password }), // Include password for server-side hashing
        });
  
        if (response.ok) {
            await loadUsers(); // Reload from server after successful add
            alert('User added successfully!');
        } else {
            alert('Error adding user.');
        }
    } catch (error) {
        console.error('Error adding user:', error);
    } finally {
        closeAddUserModal();
    }
}
    
// Update user details
async function updateUser(event) {
    event.preventDefault();
  
    const user_id = document.getElementById('updateUserId').value;
    const username = document.getElementById('updateUsername').value;
    const firstname = document.getElementById('updateFirstName').value;
    const lastname = document.getElementById('updateLastName').value;
    const email = document.getElementById('updateEmail').value;
    const role = document.getElementById('updateRole').value;
  
    const userIndex = users.findIndex(user => user.user_id === parseInt(user_id));
  
    if (userIndex === -1) {
        alert('User not found!');
        return;
    }
  
    // Update the local `users` array
    users[userIndex] = {
        ...users[userIndex],
        username,
        firstname,
        lastname,
        email,
        role,
    };
  
    loadUsers(users); // Re-render the table
  
    try {
        const response = await fetch(`/api/users/${user_id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(users[userIndex]),
        });
  
        if (response.ok) {
            await loadUsers(); // Reload from server after successful update
            alert('User updated successfully!');
        } else {
            alert('Error updating user.');
        }
    } catch (error) {
        console.error('Error updating user:', error);
    } finally {
        closeUpdateUserModal();
    }
}
  
// Delete user
async function deleteUser(user_id) {
    // Remove user from the `users` array
    const userIndex = users.findIndex(user => user.user_id === user_id);
  
    if (userIndex === -1) {
        alert('User not found!');
        return;
    }
  
    users.splice(userIndex, 1);
    loadUsers(users); // Re-render the table
  
    try {
        const response = await fetch(`/api/users/${user_id}`, {
            method: 'DELETE',
        });
  
        if (response.ok) {
            alert('User deleted successfully!');
        } else {
            alert('Error deleting user.');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
    }
}
  
// Function to open the Add User Modal
function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
        modal.style.display = 'block'; // Show the modal
    } else {
        console.error('Add User Modal not found!');
    }
}

// Open the Update User Modal and pre-fill with user data
function openUpdateModal(user_id) {
    // Find the user by ID
    const user = users.find(u => u.user_id === user_id);
    console.log('Opening update modal for user:', user); // Debug log

    if (!user) {
        console.error('User not found:', user_id);
        return;
    }

    // Get the modal element
    const modal = document.getElementById('updateUserModal');
    if (!modal) {
        console.error('Update modal element not found!');
        return;
    }

    try {
        // Fill the form fields with user data
        document.getElementById('updateUserId').value = user.user_id;
        document.getElementById('updateUsername').value = user.username;
        document.getElementById('updateFirstName').value = user.firstname;
        document.getElementById('updateLastName').value = user.lastname;
        document.getElementById('updateEmail').value = user.email;
        document.getElementById('updateRole').value = user.role;

        // Show the modal
        modal.style.display = 'block';
    } catch (error) {
        console.error('Error populating update form:', error);
    }
}
  
// Close the "Add User" modal
function closeAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) modal.style.display = 'none';
}
  
// Close the "Update User" modal
function closeUpdateUserModal() {
    const modal = document.getElementById('updateUserModal');
    if (modal) modal.style.display = 'none';
}

function initializeFilters() {
    const roleSelect = document.getElementById('filterRole');
    const roles = ['Admin', 'Manager', 'Student', 'Faculty', 'Employee'];
    
    // Clear existing options
    roleSelect.innerHTML = '<option value="">Filter by Role</option>';
    
    // Add role options
    roles.forEach(role => {
        const option = document.createElement('option');
        option.value = role;
        option.textContent = role;
        roleSelect.appendChild(option);
    });
}

function filterUsers() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('filterRole').value;
    const userTypeFilter = document.getElementById('filterUserType').value;

    const filteredUsers = users.filter(user => {
        const matchesSearch = 
            user.username.toLowerCase().includes(searchInput) ||
            user.firstname.toLowerCase().includes(searchInput) ||
            user.lastname.toLowerCase().includes(searchInput);
            
        const matchesRole = !roleFilter || user.role === roleFilter;
        const matchesUserType = !userTypeFilter || user.user_type === userTypeFilter;

        return matchesSearch && matchesRole && matchesUserType;
    });

    renderFilteredUsers(filteredUsers);
}

function renderFilteredUsers(filteredUsers) {
    const userTableBody = document.getElementById('userTableBody');
    userTableBody.innerHTML = '';

    if (filteredUsers.length === 0) {
        userTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="no-results">No users found matching the filters</td>
            </tr>
        `;
        return;
    }

    filteredUsers.forEach(user => {
        const avatar = user.avatar ? `<img src="${user.avatar}" alt="${user.username}'s avatar">` : 'N/A';
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.username}</td>
            <td>${user.firstname}</td>
            <td>${user.lastname}</td>
            <td>${user.email}</td>
            <td>${user.role}</td>
            <td>${user.password}</td>
            <td>${avatar}</td>
            <td>
                <button class="edit-button" data-userid="${user.user_id}">Edit</button>
                <button class="delete-button" data-userid="${user.user_id}">Delete</button>
            </td>
        `;

        // Add event listeners to the buttons
        const editButton = row.querySelector('.edit-button');
        const deleteButton = row.querySelector('.delete-button');

        editButton.addEventListener('click', () => openUpdateModal(user.user_id));
        deleteButton.addEventListener('click', () => deleteUser(user.user_id));

        userTableBody.appendChild(row);
    });
}

function applyFilters() {
    filterUsers();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterRole').value = '';
    document.getElementById('filterUserType').value = '';
    filterUsers();
}

document.addEventListener('DOMContentLoaded', loadUsers);

  
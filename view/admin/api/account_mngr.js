//Author: Chryzel Beray
let users = [];
document.addEventListener('DOMContentLoaded', () => {
    loadUsers(); // Load users when the page is loaded
  
    // Form submissions
    document.getElementById('addUserForm')?.addEventListener('submit', addUser);
    document.getElementById('updateUserForm')?.addEventListener('submit', updateUser);
  });
  
  // Load users from the API and populate the table
  async function loadUsers() {
    try {
      const response = await fetch('/api/users');
      users = await response.json(); // Store users globally
      const userTableBody = document.getElementById('userTableBody');
      
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
    const firstname = document.getElementById('firstname').value;
    const lastname = document.getElementById('lastname').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const role = document.getElementById('role').value;
  
    // Validate password match
    if (password !== confirmPassword) {
      alert('Passwords do not match!');
      return;
    }
  
    const user = { username, firstname, lastname, email, role };
  
    try {
      const response = await fetch('/api/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(user)
      });
  
      const result = await response.json();
      if (response.ok) {
        alert('User added successfully!');
        loadUsers(); // Reload users after adding
        closeAddUserModal(); // Close the modal
      } else {
        alert('Error adding user: ' + result.error);
      }
    } catch (error) {
      console.error('Error adding user:', error);
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
  
    const updatedUser = { username, firstname, lastname, email, role };
  
    try {
      const response = await fetch(`/api/users/${user_id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(updatedUser)
      });
  
      const result = await response.json();
      if (response.ok) {
        alert('User updated successfully!');
        loadUsers(); // Reload users after updating
        closeUpdateUserModal(); // Close the modal
      } else {
        alert('Error updating user: ' + result.message);
      }
    } catch (error) {
      console.error('Error updating user:', error);
    }
  }
  
  // Delete user
  async function deleteUser(user_id) {
    try {
      const response = await fetch(`/api/users/${user_id}`, {
        method: 'DELETE',
      });

      //true ano to
      const result = await response.json();
      if (response.ok) {
        alert('User deleted successfully!');
        loadUsers(); // Reload users after deleting
      } else {
        alert('Error deleting user: ' + result.error);
      }
    } catch (error) {
      console.error('Error deleting user:', error);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Attach event listeners here if needed
    document.querySelector('.add-schedule').addEventListener('click', openAddUserModal);
  });
  
  // Function to open the Add User Modal
  function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
      modal.style.display = 'block'; // Show the modal
    } else {
      console.error('Add User Modal not found!');
    }
  }
  
  // Open the "Update User" modal and pre-fill with user data
function openUpdateModal(user_id) {
  // Find the user by ID
  const user = users.find(u => u.user_id === user_id);

  // Check if the user is found
  if (user) {
    // Fill the form fields with user data
    document.getElementById('updateUserId').value = user.user_id;
    document.getElementById('updateUsername').value = user.username; // Accessing the username directly
    document.getElementById('updateFirstName').value = user.firstname;
    document.getElementById('updateLastName').value = user.lastname;
    document.getElementById('updateEmail').value = user.email;
    const roleDropdown = document.getElementById('updateRole');
    roleDropdown.value = user.role;

    // Display the modal
    const modal = document.getElementById('updateUserModal');
    if (modal) {
      modal.style.display = 'block'; // Show the modal
    } else {
      console.error('Modal element not found!');
    }
  } else {
    console.error('User not found');
  }
}
  
function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
      modal.style.display = 'block'; // Show the modal
    } else {
      console.error('Add User Modal not found!');
    }
}

  // Close the "Add User" modal
  function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
  }
  
  // Close the "Update User" modal
  function closeUpdateUserModal() {
    document.getElementById('updateUserModal').style.display = 'none';
  }

  document.addEventListener('DOMContentLoaded', loadUsers);

  
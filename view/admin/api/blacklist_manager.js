let blacklistEntries = [];

document.addEventListener('DOMContentLoaded', () => {
    loadBlacklist();
    loadUsers();
    initializeEventListeners();
});

function initializeEventListeners() {
    document.getElementById('addBlacklistForm')?.addEventListener('submit', addToBlacklist);
    document.getElementById('updateStatusForm')?.addEventListener('submit', updateStatus);
    document.getElementById('searchInput')?.addEventListener('input', filterBlacklist);
    document.getElementById('filterStatus')?.addEventListener('change', filterBlacklist);
}

async function loadBlacklist() {
    try {
        const response = await fetch('/api/blacklist');
        blacklistEntries = await response.json();
        renderBlacklist(blacklistEntries);
    } catch (error) {
        console.error('Error loading blacklist:', error);
    }
}

async function loadUsers() {
    try {
        const response = await fetch('/api/users');
        const users = await response.json();
        const userSelect = document.getElementById('userId');
        userSelect.innerHTML = '<option value="">Select User</option>';
        users.forEach(user => {
            userSelect.innerHTML += `
                <option value="${user.username}">${user.username} (${user.firstname} ${user.lastname})</option>
            `;
        });
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

function renderBlacklist(entries) {
    const tableBody = document.querySelector('#blacklistTable tbody');
    tableBody.innerHTML = '';

    entries.forEach(entry => {
        const row = document.createElement('tr');
        row.style.height = '60px';
        row.innerHTML = `
            <td style="width: 20%">${entry.username}</td>
            <td style="width: 30%">${entry.reason}</td>
            <td style="width: 15%">${entry.blacklist_status}</td>
            <td style="width: 15%">${new Date(entry.blacklist_date).toLocaleDateString()}</td>
            <td style="width: 20%">
                <button onclick="openUpdateStatusModal(${entry.blacklist_id})" 
                        class="edit-button" style="margin-right: 10px">Update Status</button>
                <button onclick="deleteFromBlacklist(${entry.blacklist_id})" 
                        class="delete-button">Remove</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

async function addToBlacklist(event) {
    event.preventDefault();
    
    const formData = {
        username: document.getElementById('userId').value,
        reason: document.getElementById('notes').value
    };

    try {
        const response = await fetch('/api/blacklist', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            await loadBlacklist();
            closeAddBlacklistModal();
            alert('User added to blacklist successfully');
        } else {
            alert('Error adding user to blacklist');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error adding user to blacklist');
    }
}

async function updateStatus(event) {
    event.preventDefault();
    
    const blacklistId = document.getElementById('updateBlacklistId').value;
    const formData = {
        blacklist_status: document.getElementById('updateStatus').value,
        reason: document.getElementById('updateNotes').value
    };

    try {
        const response = await fetch(`/api/blacklist/${blacklistId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            await loadBlacklist();
            closeUpdateStatusModal();
            alert('Status updated successfully');
        } else {
            alert('Error updating status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating status');
    }
}

async function deleteFromBlacklist(id) {
    if (!confirm('Are you sure you want to remove this entry from the blacklist?')) {
        return;
    }

    try {
        const response = await fetch(`/api/blacklist/${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            await loadBlacklist();
            alert('Entry removed from blacklist');
        } else {
            alert('Error removing entry from blacklist');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error removing entry from blacklist');
    }
}

function filterBlacklist() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;

    const filtered = blacklistEntries.filter(entry => {
        const matchesSearch = entry.username.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || entry.blacklist_status === statusFilter;

        return matchesSearch && matchesStatus;
    });

    renderBlacklist(filtered);
}

// Modal functions
function openAddBlacklistModal() {
    document.getElementById('addBlacklistModal').style.display = 'block';
}

function closeAddBlacklistModal() {
    document.getElementById('addBlacklistModal').style.display = 'none';
    document.getElementById('addBlacklistForm').reset();
}

function openUpdateStatusModal(id) {
    const entry = blacklistEntries.find(e => e.blacklist_id === id);
    if (!entry) return;

    document.getElementById('updateBlacklistId').value = id;
    document.getElementById('updateStatus').value = entry.blacklist_status;
    document.getElementById('updateNotes').value = entry.reason || '';
    document.getElementById('updateStatusModal').style.display = 'block';
}

function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').style.display = 'none';
    document.getElementById('updateStatusForm').reset();
}

function applyFilters() {
    filterBlacklist();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatus').value = '';
    filterBlacklist();
}

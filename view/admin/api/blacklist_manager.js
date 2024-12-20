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
    document.getElementById('filterReason')?.addEventListener('change', filterBlacklist);
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
                <option value="${user.user_id}">${user.username} (${user.firstname} ${user.lastname})</option>
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
        row.innerHTML = `
            <td>${entry.username} (${entry.firstname} ${entry.lastname})</td>
            <td>${getReasonText(entry.reason_id)}</td>
            <td>${entry.notes || '-'}</td>
            <td><span class="status-badge ${entry.status}">${entry.status}</span></td>
            <td>${new Date(entry.created_at).toLocaleDateString()}</td>
            <td>
                <button onclick="openUpdateStatusModal(${entry.blacklist_id})" 
                        class="edit-button">Update Status</button>
                <button onclick="deleteFromBlacklist(${entry.blacklist_id})" 
                        class="delete-button">Remove</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function getReasonText(reasonId) {
    const reasons = {
        '0001': 'Did not pay on time',
        '0002': 'Frequent cancellations',
        '0003': 'Mass bookings'
    };
    return reasons[reasonId] || 'Unknown reason';
}

async function addToBlacklist(event) {
    event.preventDefault();
    
    const formData = {
        user_id: document.getElementById('userId').value,
        reason_id: document.getElementById('reasonId').value,
        notes: document.getElementById('notes').value
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
        status: document.getElementById('updateStatus').value,
        notes: document.getElementById('updateNotes').value
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
    const reasonFilter = document.getElementById('filterReason').value;

    const filtered = blacklistEntries.filter(entry => {
        const matchesSearch = 
            entry.username.toLowerCase().includes(searchTerm) ||
            entry.firstname.toLowerCase().includes(searchTerm) ||
            entry.lastname.toLowerCase().includes(searchTerm);
            
        const matchesStatus = !statusFilter || entry.status === statusFilter;
        const matchesReason = !reasonFilter || entry.reason_id === reasonFilter;

        return matchesSearch && matchesStatus && matchesReason;
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
    document.getElementById('updateStatus').value = entry.status;
    document.getElementById('updateNotes').value = entry.notes || '';
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
    document.getElementById('filterReason').value = '';
    filterBlacklist();
}

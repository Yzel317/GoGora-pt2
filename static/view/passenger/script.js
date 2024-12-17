// Switch between forms (register and login)
document.getElementById('show-login-form').addEventListener('click', function (event) {
    event.preventDefault();
    document.getElementById('registration-form').style.display = 'none';
    document.getElementById('login-form').style.display = 'block';
});

document.getElementById('show-register-form').addEventListener('click', function (event) {
    event.preventDefault();
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('registration-form').style.display = 'block';
});

function validatePasswordLength(password) {
    return password.length >= 8 && password.length <= 16;
}

// Handle registration form submission
document.getElementById('registration-form').addEventListener('submit', function (event) {
    const password = document.getElementById('password').value;

    // Check password length
    if (!validatePasswordLength(password)) {
        alert('Password must be between 8 and 16 characters.');
        event.preventDefault(); // Prevent form submission
        return;
    }

    // Create a FormData object to collect the form data
    const formData = new FormData(this);

    // Send form data to register.php via AJAX
    fetch('./static/control/includes/register.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (confirm(data.message)) {
                    window.location.href = 'manage.php';
                }
            } else {
                confirm(data.message);
            }
        })
      
});

// Handle login form submission
document.getElementById('login-form-element').addEventListener('submit', function (event) {
    const password = document.getElementById('login-password').value;

    // Check password length
    if (!validatePasswordLength(password)) {
        alert('Password must be between 8 and 15 characters.');
        event.preventDefault(); // Prevent form submission
        return;
    }

    const formData = new FormData(this);

    fetch('./static/control/includes/login.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (confirm(data.message)) {
                    window.location.href = 'booking.php'; // Redirect to booking page
                }
            } else {
                confirm(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            confirm('Login failed. Please try again.');
        });
});

// // Handle photo upload form submission
// document.getElementById('photo-upload-form').addEventListener('submit', function (event) {
//     event.preventDefault();
//     // You can add photo verification logic here before redirecting
//     // Redirect to the verification page
//     window.location.href = "verification.html";
// });

// // Verification page function to redirect to dashboard
// function redirectToHome() {
//     window.location.href = "dashboard.html"; // Redirect to dashboard or home page
// }

// function selectAvatar(element, avatarPath) {
//     // Remove 'selected' class from all avatars
//     document.querySelectorAll('.avatar-icon').forEach(icon => icon.classList.remove('selected'));

//     // Add 'selected' class to the clicked avatar
//     element.classList.add('selected');

//     // Change the current avatar image to the one selected
//     document.getElementById('current-avatar').src = avatarPath;

//     // Set the selected avatar path into the hidden input (for saving)
//     document.getElementById('selected-avatar').value = avatarPath;
// }

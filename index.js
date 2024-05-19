function showLoginForm() {
    document.getElementById('loginForm').style.display = 'block';
}

function login() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    // Check if username and password are correct
    if (username === 'Chieri' && password === 'Radiant') {
        alert('Login successful! You will now be redirected to the home page.');
        window.location.href = 'home.html'; // Redirect to home.html upon successful login
    } else {
        alert('Invalid username or password. Please try again.');
    }
}

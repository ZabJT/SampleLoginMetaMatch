document.addEventListener('DOMContentLoaded', function () {
    const loginButton = document.getElementById('login-button'); // Create account button
    const registerButton = document.getElementById('register-button'); // Sign in account button
    const loginPage = document.getElementById('login'); // Login page
    const registerPage = document.getElementById('register'); // Register page

    // Function to show the register page
    loginButton.addEventListener('click', function () {
        loginPage.style.display = 'none';
        registerPage.style.display = 'block'; // Show register page
    });

    // Function to show the login page
    registerButton.addEventListener('click', function () {
        registerPage.style.display = 'none'; // Hide register page
        loginPage.style.display = 'block'; // Show login page
    });

    // Function to toggle password visibility
    window.togglePassword = function (inputId, icon) {
        const passwordInput = document.getElementById(inputId);
        if (passwordInput.type === "password") {
            passwordInput.type = "text"; // Show password
            icon.classList.remove("fa-lock");
            icon.classList.add("fa-unlock");
        } else {
            passwordInput.type = "password"; // Hide password
            icon.classList.remove("fa-unlock");
            icon.classList.add("fa-lock");
        }
    };

    // Check for the query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const loginError = urlParams.get('error'); // Check for login error status

    // If the registration was successful, show the login page and alert
    if (status === 'registered') {
        alert('Your account has been successfully created! Please log in.');
        loginPage.style.display = 'block'; // Ensure login page is displayed
        registerPage.style.display = 'none'; // Hide register page
    }

    // If the login failed, show an alert
    if (loginError === 'login_failed') {
        alert('Incorrect email or password. Please try again.');
        loginPage.style.display = 'block'; // Ensure login page is displayed
        registerPage.style.display = 'none'; // Hide register page
    }
});

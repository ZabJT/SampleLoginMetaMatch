document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('login-button'); // Create account button
    const registerButton = document.getElementById('register-button'); // Sign in account button
    const loginPage = document.getElementById('login'); // Login page
    const registerPage = document.getElementById('register'); // Register page

    // Function to show the register page
    loginButton.addEventListener('click', function() {
        loginPage.style.display = 'none'; 
        registerPage.style.display = 'block'; // Show register page
    });

    // Function to show the login page
    registerButton.addEventListener('click', function() {
        registerPage.style.display = 'none'; // Hide register page
        loginPage.style.display = 'block'; // Show login page
    });

    // Function to toggle password visibility
    window.togglePassword = function(inputId, icon) {
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
});
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaMatch</title>
    <link rel="icon" href="assets/metamatch.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-55" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <img src="assets/metamatch.png" alt="MetaMatch">
    </header>

    <div class="circle-shine"></div>
    
    <div class="login-page" id="login" style="display: none;">
        <div class="container sliding-register-container">
            <div class="sliding-register-content">
                <h1 class="form-title">Don't have an account?</h1>
                <p class="text-opacity">Sign in to <span class="bold-text">MetaMatch</span> and start building your perfect PC setup. Your ultimate comparison platform, tailored to your needs, is just a click away!</p>
                <br>
                <button class="btn button-transparent" id="login-button"><p>Create account</p></button>
            </div> 
        </div>
    
        <div class="container login-container">
            <div class="login-content">
                <h1 class="color-title">Log In</h1>
                <p class="text-opacity">Enter your email to receive a one-time passcode</p>
                <br>

                <?php
                    // Display success message if account is successfully registered
                    if (isset($_SESSION['success'])) {
                        echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
                        unset($_SESSION['success']); // Clear the success message after displaying it
                    }

                    // Display error message if email already exists
                    if (isset($_GET['error']) && $_GET['error'] == 'email_exists') {
                        echo '<p style="color: red;">Email address already exists!</p>';
                    }

                    // Display session error message if it exists
                    if (isset($_SESSION['error'])) {
                        echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                        unset($_SESSION['error']); // Clear the error message after displaying it
                    }
                ?>
    
                <form class="login-form" method="post" action="register.php"> 
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input class="input-type" type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock" id="toggle-password-login" onclick="togglePassword('password-login', this)"></i>
                        <input class="input-type" type="password" name="password" id="password-login" placeholder=" Enter your password" required>
                    </div>      
                    
                    
                    <p class="forgot-password">
                        <a href="#">Forgot Password?</a>
                    </p>
                    <input type="submit" class="btn button-solid" value="Log in" name="signIn">
                </form>
                <br>
                
                <div class="or-display">
                    <div class="line"></div>
                    <p style="text-align: center;">or</p>
                    <div class="line"></div>
                </div>
    
                <button class="btn button-transparent">
                    <div class="google-sign-in">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 262"><path fill="#4285f4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622l38.755 30.023l2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"/><path fill="#34a853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055c-34.523 0-63.824-22.773-74.269-54.25l-1.531.13l-40.298 31.187l-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"/><path fill="#fbbc05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82c0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602z"/><path fill="#eb4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0C79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"/></svg>        
                        <p>Sign in with Google</p>       
                    </div>
                </button>
                <br> 
                
                <div class="container-line-progress">
                    <div class="line-progress left"></div>
                    <div class="line-progress right"></div>
                </div>            
            </div>
        </div>
    </div>

    <div class="register-page" id="register" style="display: block;">
        <div class="container sliding-login-container">
            <div class="sliding-login-content">
                <h1 class="form-title">Have an account?</h1>
                <p class="text-opacity">Log in to <span class="bold-text">MetaMatch</span> and take control of your PC build. Compare, choose, and optimize your setup effortlessly!</p>
                <br>
                <button class="btn button-transparent" id="register-button"><p>Sign in Account</p></button>
            </div> 
        </div>

        <div class="container register-container">
            <div class="register-content">
                <h1 class="color-title">Register</h1>
                <p class="text-opacity">Sign up the necessary information below</p>
                <br>
                    
                <?php
                    // Display success message if account is successfully registered
                    if (isset($_SESSION['success'])) {
                        echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
                        unset($_SESSION['success']); // Clear the success message after displaying it
                    }

                    // Display error message if email already exists
                    if (isset($_GET['error']) && $_GET['error'] == 'email_exists') {
                        echo '<p style="color: red;">Email address already exists!</p>';
                    }

                    // Display session error message if it exists
                    if (isset($_SESSION['error'])) {
                        echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                        unset($_SESSION['error']); // Clear the error message after displaying it
                    }
                ?>

                <form class="register-form" method="post" action="register.php"> 
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input class="input-type" type="text" name="name" id="name" placeholder="Enter your name" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input class="input-type" type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                    <i class="fas fa-lock" id="toggle-password-register" onclick="togglePassword('password-register', this)"></i>
                    <input class="input-type" type="password" name="password" id="password-register" placeholder=" Enter your password" required>
                    </div>      
                    
                    <input type="submit" class="btn button-solid" value="Sign up" name="signUp">
                </form>
                <br>
                
                <div class="or-display">
                    <div class="line"></div>
                    <p style="text-align: center;">or</p>
                    <div class="line"></div>
                </div>
    
                <button class="btn button-transparent">
                    <div class="google-sign-in">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 262"><path fill="#4285f4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622l38.755 30.023l2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"/><path fill="#34a853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055c-34.523 0-63.824-22.773-74.269-54.25l-1.531.13l-40.298 31.187l-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"/><path fill="#fbbc05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82c0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602z"/><path fill="#eb4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0C79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"/></svg>        
                        <p>Sign in with Google</p>       
                    </div>
                </button>
                <br>            
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>

</body>
</html>
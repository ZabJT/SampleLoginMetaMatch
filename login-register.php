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
               
                <?php if (isset($_SESSION['name'])): ?>
                    <p class="text-opacity">Enter your email here,<?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
                <?php else: ?>
                    <p class="text-opacity">Enter your email here, user!</p>
                <?php endif; ?>
               
                <br>
    
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
                        <input class="input-type" type="text" name="name" id="name" placeholder="Enter your username" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input class="input-type" type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                    <i class="fas fa-lock" id="toggle-password-register" onclick="togglePassword('password-register', this)"></i>
                    <input class="input-type" type="password" name="password" id="password-register" placeholder=" Enter your password" required>
                    </div>   
                    <br>       
                    
                    <input type="submit" class="btn button-solid" value="Sign up" name="signUp">
                </form>           
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>

</body>
</html>
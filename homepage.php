<?php
    session_start();
    include("connect.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaMatch</title>
    <link rel="icon" href="assets/metamatch.png">
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-55" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="css/homepage.css">
</head>
<body>
    <header class="primary-header">
        <div class="container">
            <div class="nav-wrapper">
                <a href="#">
                    <img src="assets/metamatch.png" alt="MetaMatch">
                </a>
                <button class="mobile-nav-toggle" aria-controls="primary-nav" aria-expanded="false">
                    <i class="fa-solid fa-bars icon-hamburger" aria-hidden="true"></i>
                    <i class="fa-solid fa-xmark icon-close" aria-hidden="true"></i>
                    <span class="visually-hidden">Menu</span> 
                </button>
                <nav class="primary-nav">
                    <ul role="list" class="nav-list" id="primary-nav">
                        <li><a href="#categories">Categories</a></li>
                        <li><a href="#">Compare</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li class="mobile-only">
                            <?php if (isset($_SESSION['name'])): ?>
                                <a href="#">
                                    <p class="text-neutral fs-normal">Profile</p>
                                </a>
                            <?php else: ?>
                                <a href="login-register.php">
                                    <button class="button | display-md-inline-flex">Sign Up</button>
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>

                <div class="desktop-only">
                <?php if (isset($_SESSION['name'])): ?>
                    <button class="profile-icon" aria-hidden="true" id="profileIcon">
                        <i class="fa fa-user-circle text-neutral fs-sub-normal display-md-inline-flex display-sm-none"></i>
                    </button>
                    <div class="profile-panel" id="profilePanel">
                        <div class="profile-content">
                            <p class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
                            <a href="saved-parts.php" class="saved-parts-link">Saved Parts</a>
                            <a href="logout.php"><button class="button">Logout</button></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login-register.php">
                        <button class="button | display-sm-none display-md-inline-flex">Sign Up</button>
                    </a>
                <?php endif; ?>
                </div>

            <div class="arrow-up">
                <button class="button-arrow">
                    <a href="#"><i class="fa-solid fa-caret-up"></i></a>
                </button>
            </div>
        </div>
    </header>

    <main>
        <section>
            <div class="container">
                <div class="even-columns padding-block">
                    <div class="flow">
                        <h1 class="text-primary-heading fw-bold fs-primary-heading title">
                            Compare. <br>
                            Analyze. <br>
                            Inspire. <br>
                        </h1>
                        <p class="text-neutral fw-regular fs-normal topic">
                            The ultimate gateway for comparing and choosing the best computer parts. Click the button below to learn more.
                        </p>
                        <button class="button">Build Now</button>
                    </div>

                    <div class="slider">
                        <div class="slider-inner">
                            <div class="slider-item slider-item-active">
                                <img src="assets/ram-part.png" alt="ram" class="slider-img">
                            </div>
                            <div class="slider-item">
                                <img src="assets/cpu-part.png" alt="ram" class="slider-img">
                            </div>
                            <div class="slider-item">
                                <img src="assets/gpu-part.png" alt="ram" class="slider-img">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>

        <section class="padding-block about-us" id="about">
            <div class="container">
                <div class="even-columns">
                    <div class="reveal">
                        <img src="assets/circle-infos.png" alt="circle-info">
                    </div>
                    
                    <div class="flow about-us">
                        <h1 class="reveal text-primary-heading fw-bold fs-secondary-heading title">
                            About Us
                        </h1>
                        <p class="reveal text-neutral fw-light fs-normal">
                            <span class="fw-bold">MetaMatch</span> is dedicated to helping you find the best PC parts for your needs. MetaMatch is a comparison platform, providing your needs to the next PC set up. By providing accurate information in an easy-to-visualize way.
                        </p>
                        <ul class="reveal" role="list">
                            <li>
                                <div class="flex-text">
                                    <span class="line line-about"></span>
                                    <p class="text-neutral fw-light fs-normal">
                                        <span class="fw-bold">The Problem:</span> Choosing computer parts is tough due to limited access to accurate data and comparisons.
                                    </p>
                                </div>
  
                            </li>
                            <li>
                                <div class="flex-text">
                                    <span class="line line-about"></span>
                                    <p class="text-neutral fw-light fs-normal">
                                        <span class="fw-bold">Our Solution:</span> We provide reliable, detailed comparisons to simplify your decision-making process.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="flex-text">
                                    <span class="line line-about"></span>
                                    <p class="text-neutral fw-light fs-normal">
                                        <span class="fw-bold">Meet the Team:</span> We provide reliable, detailed comparisons to simplify your decision-making process.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="padding-block" id="categories">
            <div class="container">
                <h1 class="text-primary-heading fw-bold fs-secondary-heading text-center">
                    Categories
                </h1>

                <div class="carousel">
                    <div class="list reveal">
                        
                        <!-- item 1 -->
                        <div class="item">
                            <a href="ram-categories.php" class="carousel-item">
                                <img src="assets/ram.png" alt="ram">
                                <p class="hover-text text-neutral fw-regular">Click to know more about RAMs</p>
                            </a>
                            <div class="intro">
                                <div class="title text-neutral fw-bold fs-sub-normal">
                                    Random Access Memory
                                </div>
                            </div>
                        </div>

                        <!-- item 2 -->
                        <div class="item">
                            <a href="cpu-categories.php" class="carousel-item">
                                <img src="assets/cpu.png" alt="ram">
                                <p class="hover-text text-neutral fw-regular">Click to know more about CPUs</p>
                            </a>
                            <div class="intro">
                                <div class="title text-neutral fw-bold fs-sub-normal">
                                    Central Processing Unit
                                </div>
                            </div>
                        </div>

                        <!-- item 3 -->
                        <div class="item">
                            <a href="gpu-categories.php" class="carousel-item">
                                <img src="assets/gpu.png" alt="ram">
                                <p class="hover-text text-neutral fw-regular">Click to know more about GPUs</p>
                            </a>
                            <div class="intro">
                                <div class="title text-neutral fw-bold fs-sub-normal">
                                    Graphical Processing Unit
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="arrows">
                        <button id="prev"><</button>
                        <button id="next">></button>
                    </div>
                </div>
            </div>      
        </section>

        <section>
            <div class="parallax"></div>      
        </section>

        <footer class="text-neutral padding-block" id="contact">
            <img src="assets/footer-metamatch.svg" alt="">
            <div class="container">
                <div class="even-columns">
                    <div class="flow col">
                        <p class="fw-bold fs-sub-normal">
                            About
                        </p>
                        <p class="fw-light fs-normal">
                            Get in touch with us for any inquiries, feedback, or suggestions. We value your input and are here to assist you.
                        </p>
                        <div class="icons">
                            <img src="assets/Facebook.svg" alt="Facebook">
                            <img src="assets/Instagram.svg" alt="Instagram">
                            <img src="assets/Twitter.svg" alt="Twitter">
                            <img src="assets/Email.svg" alt="Email">
                        </div>
                    </div>

                    <div class="flow col">
                        <p class="fw-bold fs-sub-normal">
                            Contact Us
                        </p>
                        <div class="contact-us">
                            <i class="fa-solid fa-location-dot fs-normal  text-accent">
                                <p class="fw-light fs-normal text-neutral">
                                    551 F Jhocson St, Sampaloc, Manila, 1008 Metro Manila
                                </p>
                            </i>
                            <i class="fa fa-phone fs-normal text-accent" aria-hidden="true">
                                <p class="fw-light fs-normal text-neutral">
                                    +63 916 253 1646
                                </p>
                            </i>
                        </div>

                    </div>

                    <div class="flow col">
                        <p class="fw-bold fs-sub-normal">
                            Support
                        </p>
                        <p class="fw-light fs-normal">
                            Terms of Service <br>
                            Privacy Policy <br>
                            FAQs <br>
                        </p>
                    </div>

                </div>

                <div class="copyright-margin">
                    
                    <span class="line line-contact"></span>
                    <div class="copyright-flex">
                        
                        <p class="copyright">
                            Copyright © 2024 Philippines. All Rights Reserved.
                        </p>

                        <div class="sponsors">
                            <img src="assets/sponsor1.png" alt="sponsor1">
                            <img src="assets/sponsor2.png" alt="sponsor2">
                            <img src="assets/sponsor3.png" alt="sponsor3">
                            <img src="assets/sponsor4.png" alt="sponsor4">
                            <img src="assets/sponsor5.png" alt="sponsor5">         
                        </div>
                    </div>
                </div>   
            </div>
        </footer>

        <script src="js/homepage.js"></script>
        
    </main>
    
</body>
</html>
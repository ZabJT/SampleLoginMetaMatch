<?php
session_start();
require 'connect.php';

// Get the selected sort option from the query string
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'price_low_to_high';

// Determine the ORDER BY clause based on the selected sorting option
switch ($sortOption) {
    case 'price_low_to_high':
        $order = "ORDER BY cpu_price ASC";
        break;
    case 'price_high_to_low':
        $order = "ORDER BY cpu_price DESC";
        break;
    case 'newest_first':
        $order = "ORDER BY cpu_date DESC";
        break;
    case 'highest_rated':
        $order = "ORDER BY rating DESC";
        break;
    case 'cpu_performance':
        $order = "ORDER BY cpu_performance DESC";
        break;
    case 'brand':
        $order = "ORDER BY cpu_brand ASC";
        break;
    default:
        $order = "ORDER BY cpu_price ASC";
}

// Fetch the data from the database with the selected order
$query = "SELECT * FROM CPU $order";
$rows = mysqli_query($conn, $query);

// Add error handling for SQL query
if (!$rows) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="icon" href="assets/metamatch.png">
    <link rel="stylesheet" href="css/categories.css">
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-55" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <script>
        function applySort() {
            const sort = document.getElementById('sort').value;
            console.log('Selected Sort:', sort); // Debugging output
            window.location.href = 'cpu-categories.php?sort=' + sort;
        }
    </script>
</head>
<body>
<header class="primary-header">
        <div class="container">
            <div class="nav-wrapper">
                <a href="homepage.php">
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
            <div class="even-flex padding-block">
                <div class="path fw-light fs-normal text-neutral">
                    <a href="homepage.php">Home</a> > <a href="homepage.php">Categories</a> > <div class="fw-bold">Central Processing Unit</div>
                </div>

                <!-- Sorting Dropdown -->
            <div class="sort-all">
                <label for="sort" class="sort-label fw-regular">Sort By:</label>
                    <div class="custom-select-wrapper">
                        <select name="sort" id="sort" class="custom-select" onchange="applySort()">
                            <option value="price_high_to_low" <?php if ($sortOption == 'price_high_to_low') echo 'selected'; ?>>Price: High to Low</option>
                            <option value="price_low_to_high" <?php if ($sortOption == 'price_low_to_high') echo 'selected'; ?>>Price: Low to High</option>
                            <option value="newest_first" <?php if ($sortOption == 'newest_first') echo 'selected'; ?>>Newest First</option>
                            <option value="highest_rated" <?php if ($sortOption == 'highest_rated') echo 'selected'; ?>>Highest Rated</option>
                            <option value="cpu_performance" <?php if ($sortOption == 'cpu_performance') echo 'selected'; ?>>CPU Performance</option>
                            <option value="brand" <?php if ($sortOption == 'brand') echo 'selected'; ?>>Brand</option>
                        </select>
                        <span class="caret"><i class="fa-solid fa-caret-down"></i></span>
                    </div>     
                </div>
            </div>

            <div>
                <h1 class="text-primary-heading fw-bold fs-primary-heading title">
                    Central Processing Unit
                </h1>
            </div>
        </div>
    </section>

    <!-- Cards Section -->
    <section class="ram-cards-container">
        <div class="grid">
            <?php foreach ($rows as $row) : ?>
                <div class="ram-card">
                    <div class="ram-card-content">
                        <h2 class="ram-card-title fw-bold text-neutral fs-normal"><?php echo htmlspecialchars($row['cpu_name']); ?></h2>
                        <img src="img/<?php echo htmlspecialchars($row['cpu_image']); ?>" alt="<?php echo htmlspecialchars($row['cpu_name']); ?>" class="ram-card-image">
                        <div class="ram-card-detail">
                            <p class="text-neutral fw-regular">
                                Cores: <?php echo htmlspecialchars($row['cpu_cores']); ?>
                            </p>
                            <p class="text-neutral fw-regular">
                                Clock Speed: <?php echo htmlspecialchars($row['cpu_clock_speed']); ?> GHz
                            </p>
                            <p class="text-neutral fw-regular">
                               Socket: <?php echo htmlspecialchars($row['cpu_socket']); ?>
                            </p>
                            <p class="text-neutral fw-regular">
                                Technology: <?php echo htmlspecialchars($row['cpu_technology']); ?>
                            </p>
                            <p class="text-neutral fw-regular">
                                Brand: <?php echo htmlspecialchars($row['cpu_brand']); ?>
                            </p>
                            <p class="text-neutral fw-regular">
                                Date Released: <?php echo htmlspecialchars($row['cpu_date']); ?>
                            </p>
                            <p class="text-neutral fw-regular">
                                Price: PHP <?php echo htmlspecialchars(number_format($row['cpu_price'], 2)); ?>
                            </p>
                        </div>
                        <div class="button-card">
                            <button class="button save-button">Save</button>
                            <button class="button compare-button">Compare</button>
                        </div>    

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<script src="js/categories.js"></script>
</body>
</html>

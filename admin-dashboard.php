<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Security check to ensure only admins can access
if ($_SESSION['role'] !== 'admin') {
  header("Location: login-register.php");
  exit();
}

// Database connection
include 'connect.php';

// Sorting logic for GPUs
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'price_low_to_high';
switch ($sortOption) {
    case 'price_low_to_high':
        $order = "ORDER BY gpu_price ASC";
        break;
    case 'price_high_to_low':
        $order = "ORDER BY gpu_price DESC";
        break;
    case 'newest_first':
        $order = "ORDER BY gpu_date DESC";
        break;
    case 'highest_rated':
        $order = "ORDER BY rating DESC";
        break;
    case 'gpu_performance':
        $order = "ORDER BY gpu_performance DESC";
        break;
    case 'brand':
        $order = "ORDER BY gpu_brand ASC";
        break;
    default:
        $order = "ORDER BY gpu_price ASC";
}

// Fetch GPU data with sorting
$query = "SELECT * FROM GPU $order";
$rows = mysqli_query($conn, $query);
if (!$rows) {
    die("Query failed: " . mysqli_error($conn));
}

// Directory check
if (!is_writable('img/')) {
    die("Directory 'img/' is not writable.");
}

// Add new GPU functionality
if (isset($_POST["submit"])) {
    $gpu_name = $_POST['gpu_name'];
    $gpu_brand = $_POST['gpu_brand'];
    $gpu_performance = $_POST['gpu_performance'];
    $gpu_type = $_POST['gpu_type'];
    $gpu_date = $_POST['gpu_date'];
    $gpu_price = $_POST['gpu_price'];

    if ($_FILES["gpu_image"]["error"] === 4) {
        echo "<script>alert('Image does not exist');</script>";
    } else {
        $fileName = $_FILES["gpu_image"]["name"];
        $fileSize = $_FILES["gpu_image"]["size"];
        $tmpName = $_FILES["gpu_image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script>alert('Invalid image extension');</script>";
        } elseif ($fileSize > 10000000) {
            echo "<script>alert('Image size is too large');</script>";
        } else {
            $newImageName = uniqid() . '.' . $imageExtension;
            if (!is_dir('img')) {
                mkdir('img', 0777, true);
            }
            if (move_uploaded_file($tmpName, 'img/' . $newImageName)) {
                $stmt = $conn->prepare("INSERT INTO GPU (gpu_name, gpu_brand, gpu_chipset, gpu_memory_size, gpu_base_clock, gpu_boost_clock, gpu_shaders, gpu_type, gpu_date, gpu_image, gpu_price) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "sssssss",
                    $gpu_name,
                    $gpu_brand,
                    $gpu_chipset,
                    $gpu_memory_size,
                    $gpu_base_clock,
                    $gpu_boost_clock,
                    $gpu_shaders,
                    $gpu_type,
                    $gpu_date,
                    $newImageName,
                    $gpu_price
                );

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Successfully added');
                            document.location.href = 'admin-dashboard.php';
                          </script>";
                } else {
                    echo "<script>alert('Database error: " . $stmt->error . "');</script>";
                }
            } else {
                echo "<script>alert('Failed to upload image');</script>";
            }
        }
    }
}

// Delete functionality for GPU
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM GPU WHERE gpu_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
        header('Location: admin-dashboard.php');
        exit();
    } else {
        echo "<script>alert('Error deleting record: " . $stmt->error . "');</script>";
    }
}

// Edit functionality for GPU
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $editQuery = "SELECT * FROM GPU WHERE gpu_id = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $editRow = $result->fetch_assoc();

    if ($editRow) {
        $gpu_name = $editRow['gpu_name'];
        $gpu_brand = $editRow['gpu_brand'];
        $gpu_performance = $editRow['gpu_performance'];
        $gpu_type = $editRow['gpu_type'];
        $gpu_date = $editRow['gpu_date'];
        $gpu_price = $editRow['gpu_price'];
    } else {
        echo "<script>alert('No GPU found with this ID.');</script>";
        header('Location: admin-dashboard.php');
        exit();
    }
}

if (isset($_POST['edit_submit'])) {
    $editId = $_POST['gpu_id'];
    $gpu_name = $_POST['gpu_name'];
    $gpu_brand = $_POST['gpu_brand'];
    $gpu_performance = $_POST['gpu_performance'];
    $gpu_type = $_POST['gpu_type'];
    $gpu_date = $_POST['gpu_date'];
    $gpu_price = $_POST['gpu_price'];

    $updateQuery = "UPDATE GPU SET gpu_name = ?, gpu_brand = ?, gpu_performance = ?, gpu_type = ?, gpu_date = ?, gpu_price = ? WHERE gpu_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(
        "ssssssi",
        $gpu_name,
        $gpu_brand,
        $gpu_performance,
        $gpu_type,
        $gpu_date,
        $gpu_price,
        $editId
    );
    
    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully');</script>";
        header('Location: admin-dashboard.php');
        exit();
    } else {
        echo "<script>alert('Error updating record: " . $stmt->error . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/adminpage.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo">
        <img src="assets/metamatch.png" alt="Logo">
      </div>
      <nav class="nav-links">
        <a href="#" data-category="cpu">CPU</a>
        <a href="#" data-category="gpu">GPU</a>
        <a href="#" data-category="ram">RAM</a>
      </nav>
      <a href="logout.php"><button class="button">Logout</button></a>
    </aside>
    
    <main class="main-content">
      <div class="crud-header">
        <h1 class="fw-bold text-neutral fs-sub-normal">Graphics Processing Unit (GPU)</h1>
        <div class="crud-buttons">
          <button id="addBtn" onclick="toggleAddGPUForm()">Add</button>
        </div>
      </div>

      <div class="sort-all">
        <label for="sort" class="sort-label fw-regular">Sort By:</label>
        <select name="sort" id="sort" class="custom-select" onchange="applySort()">
          <option value="price_high_to_low" <?php if ($sortOption == 'price_high_to_low') echo 'selected'; ?>>Price: High to Low</option>
          <option value="price_low_to_high" <?php if ($sortOption == 'price_low_to_high') echo 'selected'; ?>>Price: Low to High</option>
          <option value="newest_first" <?php if ($sortOption == 'newest_first') echo 'selected'; ?>>Newest First</option>
          <option value="highest_rated" <?php if ($sortOption == 'highest_rated') echo 'selected'; ?>>Highest Rated</option>
          <option value="gpu_performance" <?php if ($sortOption == 'gpu_performance') echo 'selected'; ?>>GPU Performance</option>
          <option value="brand" <?php if ($sortOption == 'brand') echo 'selected'; ?>>Brand</option>
        </select>
        <span class="caret"><i class="fa-solid fa-caret-down"></i></span>
      </div>

      <!-- Add GPU Form -->
      <div id="addGPUFormModal" class="modal">
        <div class="modal-content">
          <span class="close-btn" onclick="closeAddGPUForm()">&times;</span>
          <h2 class="fw-bold fs-normal">Upload GPU Details</h2>
          <form action="admin-dashboard.php" method="POST" enctype="multipart/form-data">
    <label for="gpu_name">GPU Name:</label>
    <input type="text" name="gpu_name" id="gpu_name" required><br><br>

    <label for="gpu_brand">GPU Brand:</label>
    <input type="text" name="gpu_brand" id="gpu_brand" required><br><br>

    <label for="gpu_chipset">GPU Chipset:</label>
    <input type="text" name="gpu_chipset" id="gpu_chipset" required><br><br>

    <label for="gpu_memory_size">GPU Memory Size:</label>
    <input type="text" name="gpu_memory_size" id="gpu_memory_size" required><br><br>

    <label for="gpu_base_clock">GPU Base Clock:</label>
    <input type="text" name="gpu_base_clock" id="gpu_base_clock" required><br><br>

    <label for="gpu_boost_clock">GPU Boost Clock:</label>
    <input type="text" name="gpu_boost_clock" id="gpu_boost_clock" required><br><br>

    <label for="gpu_shaders">GPU Shaders:</label>
    <input type="text" name="gpu_shaders" id="gpu_shaders" required><br><br>

    <label for="gpu_type">GPU Type:</label>
    <input type="text" name="gpu_type" id="gpu_type" required><br><br>

    <label for="gpu_date">Release Date:</label>
    <input type="date" name="gpu_date" id="gpu_date" required><br><br>

    <label for="gpu_image">GPU Image:</label>
    <input type="file" name="gpu_image" id="gpu_image" accept=".jpg, .jpeg, .png" required><br><br>

    <label for="gpu_price">GPU Price (in PHP):</label>
    <input type="number" step="0.01" name="gpu_price" id="gpu_price" required><br><br>

    <button type="submit" name="submit">Upload GPU</button>
</form>

        </div>
      </div>

      <div class="crud-table">
        <table id="gpuTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Brand</th>
              <th>Name</th>
              <th>Chipset</th>
              <th>Memory Size</th>
              <th>Base Clock</th>
              <th>Boost Clock</th>
              <th>Shaders</th>
              <th>Type</th>
              <th>Price</th>
              <th>Release Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="gpuTableBody">
            <?php 
            $count = 1;
            while ($row = mysqli_fetch_assoc($rows)): ?>
              <tr>
                <td><?php echo $count++; ?></td>
                <td>
                  <img src="img/<?php echo htmlspecialchars($row['gpu_image']); ?>" alt="GPU Image" style="width: 50px; height: auto;">
                </td>
                <td><?php echo htmlspecialchars($row['gpu_brand']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_name']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_chipset']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_memory_size']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_base_clock']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_boost_clock']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_shaders']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_date']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_price']); ?></td>
                <td><?php echo htmlspecialchars($row['gpu_date']); ?></td>
                <td>
                    <a href="admin-dashboard.php?edit_id=<?php echo $row['gpu_id']; ?>" onclick="openEditModal(event, '<?php echo $row['gpu_id']; ?>')" class="edit-link"> 
                        <i class="edit-button fas fa-edit"></i> 
                    </a>
                    <a href="admin-dashboard.php?delete_id=<?php echo $row['gpu_id']; ?>" onclick="return confirm('Are you sure you want to delete this?')">
                        <i class="delete-button fas fa-trash-alt"></i>
                    </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script src="js/adminpage.js"></script>
</body>
</html>

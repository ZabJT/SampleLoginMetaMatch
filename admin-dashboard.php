<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['name']) || $_SESSION['role'] !== 'admin') {
    header('Location: login-register.php');
    exit;
}

// Database connection
include 'connect.php';

// Sorting logic
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'price_low_to_high';
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
    case 'brand':
        $order = "ORDER BY cpu_brand ASC";
        break;
    default:
        $order = "ORDER BY cpu_price ASC";
}

// Fetch CPU data with sorting
$query = "SELECT * FROM CPU $order";
$rows = mysqli_query($conn, $query);
if (!$rows) {
    die("Query failed: " . mysqli_error($conn));
}

if (!is_writable('img/')) {
    die("Directory 'img/' is not writable.");
}

if (isset($_POST["submit"])) {
    // Insert new CPU record
    $cpu_name = $_POST['cpu_name'];
    $cpu_cores = $_POST['cpu_cores'];
    $cpu_clock_speed = $_POST['cpu_clock_speed']; // for 3.1 to 3.8 GHz
    $cpu_socket = $_POST['cpu_socket'];
    $cpu_technology = $_POST['cpu_technology']; // e.g., 28nm
    $cpu_date = $_POST['cpu_date'];
    $cpu_price = $_POST['cpu_price'];
    $cpu_brand = $_POST['cpu_brand'];

    if ($_FILES["cpu_image"]["error"] === 4) {
        echo "<script>alert('Image does not exist');</script>";
    } else {
        $fileName = $_FILES["cpu_image"]["name"];
        $fileSize = $_FILES["cpu_image"]["size"];
        $tmpName = $_FILES["cpu_image"]["tmp_name"];

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
                $stmt = $conn->prepare("INSERT INTO CPU (cpu_name, cpu_cores, cpu_clock_speed, cpu_socket, cpu_technology, cpu_date, cpu_image, cpu_price, cpu_brand) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "sssssssss",
                    $cpu_name,
                    $cpu_cores,
                    $cpu_clock_speed,
                    $cpu_socket,
                    $cpu_technology,
                    $cpu_date,
                    $newImageName,
                    $cpu_price,
                    $cpu_brand
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

// Delete functionality
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM CPU WHERE cpu_id = ?";
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

// Edit functionality
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $editQuery = "SELECT * FROM CPU WHERE cpu_id = ?";
    $stmt = $conn->prepare($editQuery);
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    $editRow = $result->fetch_assoc();
    $cpu_name = $editRow['cpu_name'];
    $cpu_cores = $editRow['cpu_cores'];
    $cpu_clock_speed = $editRow['cpu_clock_speed'];
    $cpu_socket = $editRow['cpu_socket'];
    $cpu_technology = $editRow['cpu_technology'];
    $cpu_date = $editRow['cpu_date'];
    $cpu_price = $editRow['cpu_price'];
    $cpu_brand = $editRow['cpu_brand'];
}

// Update functionality after form submission
if (isset($_POST['edit_submit'])) {
    $editId = $_POST['cpu_id'];
    $cpu_name = $_POST['cpu_name'];
    $cpu_cores = $_POST['cpu_cores'];
    $cpu_clock_speed = $_POST['cpu_clock_speed'];
    $cpu_socket = $_POST['cpu_socket'];
    $cpu_technology = $_POST['cpu_technology'];
    $cpu_date = $_POST['cpu_date'];
    $cpu_price = $_POST['cpu_price'];
    $cpu_brand = $_POST['cpu_brand'];

    $updateQuery = "UPDATE CPU SET cpu_name = ?, cpu_cores = ?, cpu_clock_speed = ?, cpu_socket = ?, cpu_technology = ?, cpu_date = ?, cpu_price = ?, cpu_brand = ? WHERE cpu_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(
        "ssssssdsi", // Type definitions: s=string, d=double (for price), i=integer
        $cpu_name,
        $cpu_cores,
        $cpu_clock_speed,
        $cpu_socket,
        $cpu_technology,
        $cpu_date,
        $cpu_price, // Double for price
        $cpu_brand,
        $editId // Integer for ID
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
    </aside>
    <main class="main-content">
      <div class="crud-header">
        <h1>Dashboard</h1>
        <div class="crud-buttons">
          <button id="addBtn" onclick="toggleAddCPUForm()">Add</button>
        </div>
      </div>

      <div class="sort-all">
        <label for="sort" class="sort-label fw-regular">Sort By:</label>
        <select name="sort" id="sort" class="custom-select" onchange="applySort()">
          <option value="price_high_to_low" <?php if ($sortOption == 'price_high_to_low') echo 'selected'; ?>>Price: High to Low</option>
          <option value="price_low_to_high" <?php if ($sortOption == 'price_low_to_high') echo 'selected'; ?>>Price: Low to High</option>
          <option value="newest_first" <?php if ($sortOption == 'newest_first') echo 'selected'; ?>>Newest First</option>
          <option value="highest_rated" <?php if ($sortOption == 'highest_rated') echo 'selected'; ?>>Highest Rated</option>
          <option value="brand" <?php if ($sortOption == 'brand') echo 'selected'; ?>>Brand</option>
        </select>
      </div>

     <!-- Add Form -->
      <div id="addCPUForm" style="display: none">
        <h2>Upload CPU Details</h2>
        <form action="admin-dashboard.php" method="POST" enctype="multipart/form-data">
          <label for="cpu_name">CPU Name:</label>
          <input type="text" name="cpu_name" id="cpu_name" required><br><br>

          <label for="cpu_cores">CPU Cores:</label>
          <input type="number" name="cpu_cores" id="cpu_cores" required><br><br>

          <label for="cpu_clock_speed">Clock Speed (GHz):</label>
          <input type="text" name="cpu_clock_speed" id="cpu_clock_speed" required><br><br>

          <label for="cpu_socket">CPU Socket:</label>
          <input type="text" name="cpu_socket" id="cpu_socket" required><br><br>

          <label for="cpu_technology">Manufacturing Technology (e.g., 28nm):</label>
          <input type="text" name="cpu_technology" id="cpu_technology" required><br><br>

          <label for="cpu_date">Release Date:</label>
          <input type="date" name="cpu_date" id="cpu_date" required><br><br>

          <label for="cpu_image">CPU Image:</label>
          <input type="file" name="cpu_image" id="cpu_image" accept=".jpg, .jpeg, .png" required><br><br>

          <label for="cpu_price">CPU Price (in PHP):</label>
          <input type="number" step="0.01" name="cpu_price" id="cpu_price" required><br><br>

          <label for="cpu_brand">CPU Brand:</label>
          <input type="text" name="cpu_brand" id="cpu_brand" required><br><br>

          <button type="submit" name="submit">Upload CPU</button>
        </form>
      </div>
      

      <!-- Edit Form -->
<?php if (isset($_GET['edit_id'])): ?>
<div id="editCPUForm">
  <h2>Edit CPU Details</h2>
  <form method="POST" action="admin-dashboard.php" enctype="multipart/form-data">
    <input type="hidden" name="cpu_id" value="<?php echo $editId; ?>">

    <label for="cpu_name">CPU Name:</label>
    <input type="text" name="cpu_name" value="<?php echo htmlspecialchars($cpu_name); ?>" required><br><br>

    <label for="cpu_cores">CPU Cores:</label>
    <input type="number" name="cpu_cores" value="<?php echo htmlspecialchars($cpu_cores); ?>" required><br><br>

    <label for="cpu_clock_speed">Clock Speed (GHz):</label>
    <input type="text" name="cpu_clock_speed" value="<?php echo htmlspecialchars($cpu_clock_speed); ?>" required><br><br>

    <label for="cpu_socket">CPU Socket:</label>
    <input type="text" name="cpu_socket" value="<?php echo htmlspecialchars($cpu_socket); ?>" required><br><br>

    <label for="cpu_technology">Manufacturing Technology (e.g., 28nm):</label>
    <input type="text" name="cpu_technology" value="<?php echo htmlspecialchars($cpu_technology); ?>" required><br><br>

    <label for="cpu_date">Release Date:</label>
    <input type="date" name="cpu_date" value="<?php echo htmlspecialchars($cpu_date); ?>" required><br><br>

    <label for="cpu_image">CPU Image:</label>
    <input type="file" name="cpu_image" accept=".jpg, .jpeg, .png"><br><br>

    <label for="cpu_price">CPU Price (in PHP):</label>
    <input type="number" step="0.01" name="cpu_price" value="<?php echo htmlspecialchars($cpu_price); ?>" required><br><br>

    <label for="cpu_brand">CPU Brand:</label>
    <input type="text" name="cpu_brand" value="<?php echo htmlspecialchars($cpu_brand); ?>" required><br><br>

    <button type="submit" name="edit_submit">Update CPU</button>
  </form>
</div>
<?php endif; ?>


      <div class="crud-table">
        <table id="cpuTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Brand</th>
              <th>Name</th>
              <th>Cores</th>
              <th>Clock Speed</th>
              <th>Socket</th>
              <th>Technology</th>
              <th>Price</th>
              <th>Release Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="cpuTableBody">
            <?php 
            $count = 1;
            while ($row = mysqli_fetch_assoc($rows)): ?>
              <tr>
                <td><?php echo $count++; ?></td>
                <td>
                  <img src="img/<?php echo htmlspecialchars($row['cpu_image']); ?>" alt="CPU Image" style="width: 50px; height: auto;">
                </td>
                <td><?php echo htmlspecialchars($row['cpu_brand']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_name']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_cores']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_clock_speed']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_socket']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_technology']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_price']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu_date']); ?></td>
                <td>
                    <a href="admin-dashboard.php?edit_id=<?php echo $row['cpu_id']; ?>" onclick="toggleEditCPUForm(event, '<?php echo $row['cpu_id']; ?>')" class="edit-link">Edit</a>
                  <a href="admin-dashboard.php?delete_id=<?php echo $row['cpu_id']; ?>" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
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

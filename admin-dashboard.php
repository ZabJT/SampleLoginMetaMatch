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

// Determine category and sorting options
$category = isset($_GET['category']) ? $_GET['category'] : 'cpu';
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'price_low_to_high';

// Define table and columns based on category
switch ($category) {
    case 'cpu':
        $table = 'CPU';
        $columns = 'cpu_id, cpu_name, cpu_brand, cpu_cores, cpu_clock_speed, cpu_socket, cpu_price, cpu_date, cpu_image';
        $displayColumns = ['#', 'Name', 'Brand', 'Cores', 'Clock Speed', 'Socket', 'Price', 'Release Date', 'Image'];
        $addButtonLabel = "Add New CPU";
        break;
    case 'ram':
        $table = 'RAM';
        $columns = 'ram_id, ram_name, ram_brand, ram_capacity, ram_data_rate, ram_voltage, ram_price, ram_date, ram_image';
        $displayColumns = ['#', 'Name', 'Brand', 'Capacity', 'Speed', 'Voltage', 'Price', 'Release Date', 'Image'];
        $addButtonLabel = "Add New RAM";
        break;
    case 'gpu':
    default:
        $table = 'GPU';
        $columns = 'gpu_id, gpu_name, gpu_brand, gpu_chipset, gpu_memory_size, gpu_base_clock, gpu_price, gpu_date, gpu_image';
        $displayColumns = ['#', 'Name', 'Brand', 'Chipset', 'Memory Size', 'Base Clock', 'Price', 'Release Date', 'Image'];
        $addButtonLabel = "Add New GPU";
        break;
}

// Sorting logic
switch ($sortOption) {
    case 'price_low_to_high':
        $order = "ORDER BY {$table}_price ASC";
        break;
    case 'price_high_to_low':
        $order = "ORDER BY {$table}_price DESC";
        break;
    case 'newest_first':
        $order = "ORDER BY {$table}_date DESC";
        break;
    case 'brand':
        $order = "ORDER BY {$table}_brand ASC";
        break;
    default:
        $order = "ORDER BY {$table}_price ASC";
}

$query = "SELECT $columns FROM $table $order";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Add new item functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['component_category']) && !isset($_POST['edit_id'])) {
    $componentCategory = $_POST['component_category'];
    $newImageName = 'default-image.png';

    // Debugging: Check if the expected image field exists in the $_FILES array
    echo '<pre>';
    print_r($_FILES);
    echo '</pre>';

    // Dynamically handle image based on the selected category
    $imageField = strtolower($componentCategory) . '_image'; // 'cpu_image', 'ram_image', 'gpu_image'

    // Check if the image field exists in the $_FILES array
    if (isset($_FILES[$imageField])) {
        $file = $_FILES[$imageField];
        $imageExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $validImageExtension = ['jpg', 'jpeg', 'png'];

        if (!in_array($imageExtension, $validImageExtension)) {
            die("<script>alert('Invalid image extension');</script>");
        }
        if ($file["size"] > 10000000) { // 10MB limit
            die("<script>alert('Image size is too large');</script>");
        }

        $newImageName = uniqid() . '.' . $imageExtension;
        if (!is_dir('img')) {
            mkdir('img', 0777, true);
        }
        if (!move_uploaded_file($file["tmp_name"], 'img/' . $newImageName)) {
            die("<script>alert('Failed to upload image');</script>");
        }
    } else {
        die("<script>alert('Image not uploaded');</script>");
    }

    // Proceed with the database insert operation for different categories
    switch ($componentCategory) {
        case 'CPU':
            $stmt = $conn->prepare("INSERT INTO CPU (cpu_name, cpu_cores, cpu_clock_speed, cpu_socket, cpu_technology, cpu_date, cpu_image, cpu_price, cpu_brand) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssssssss",
                $_POST['cpu_name'],
                $_POST['cpu_cores'],
                $_POST['cpu_clock_speed'],
                $_POST['cpu_socket'],
                $_POST['cpu_technology'],
                $_POST['cpu_date'],
                $newImageName,
                $_POST['cpu_price'],
                $_POST['cpu_brand']
            );
            break;
        case 'RAM':
            $stmt = $conn->prepare("INSERT INTO RAM (ram_name, ram_capacity, ram_data_rate, ram_voltage, ram_price, ram_date, ram_image, ram_brand) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "ssssssss",
                $_POST['ram_name'],
                $_POST['ram_capacity'],
                $_POST['ram_data_rate'],
                $_POST['ram_voltage'],
                $_POST['ram_price'],
                $_POST['ram_date'],
                $newImageName,
                $_POST['ram_brand']
            );
            break;
        case 'GPU':
            $stmt = $conn->prepare("INSERT INTO GPU (gpu_name, gpu_chipset, gpu_memory_size, gpu_base_clock, gpu_price, gpu_date, gpu_image, gpu_brand) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "ssssssss",
                $_POST['gpu_name'],
                $_POST['gpu_chipset'],
                $_POST['gpu_memory_size'],
                $_POST['gpu_base_clock'],
                $_POST['gpu_price'],
                $_POST['gpu_date'],
                $newImageName,
                $_POST['gpu_brand']
            );
            break;
    }

    if ($stmt->execute()) {
        echo "<script>alert('Successfully added'); document.location.href = 'admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Database error: " . $stmt->error . "');</script>";
    }
}



// Edit item functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $componentCategory = $_POST['component_category'];
    $newImageName = null;

    if (isset($_FILES["{$componentCategory}_image"]) && $_FILES["{$componentCategory}_image"]["error"] === 0) {
        $file = $_FILES["{$componentCategory}_image"];
        $imageExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $validImageExtension = ['jpg', 'jpeg', 'png'];

        if (!in_array($imageExtension, $validImageExtension)) {
            die("<script>alert('Invalid image extension');</script>");
        }
        if ($file["size"] > 10000000) { // 10MB limit
            die("<script>alert('Image size is too large');</script>");
        }

        $newImageName = uniqid() . '.' . $imageExtension;
        move_uploaded_file($file["tmp_name"], 'img/' . $newImageName);
    }

    switch ($componentCategory) {
        case 'CPU':
            $stmt = $conn->prepare("UPDATE CPU SET cpu_name = ?, cpu_cores = ?, cpu_clock_speed = ?, cpu_socket = ?, cpu_technology = ?, cpu_date = ?, cpu_price = ?, cpu_brand = ?, cpu_image = IFNULL(?, cpu_image) WHERE cpu_id = ?");
            $stmt->bind_param(
                "sssssssssi",
                $_POST['cpu_name'],
                $_POST['cpu_cores'],
                $_POST['cpu_clock_speed'],
                $_POST['cpu_socket'],
                $_POST['cpu_technology'],
                $_POST['cpu_date'],
                $_POST['cpu_price'],
                $_POST['cpu_brand'],
                $newImageName,
                $editId
            );
            break;
        case 'RAM':
            $stmt = $conn->prepare("UPDATE RAM SET ram_name = ?, ram_capacity = ?, ram_data_rate = ?, ram_voltage = ?, ram_price = ?, ram_date = ?, ram_brand = ?, ram_image = IFNULL(?, ram_image) WHERE ram_id = ?");
            $stmt->bind_param(
                "ssssssssi",
                $_POST['ram_name'],
                $_POST['ram_capacity'],
                $_POST['ram_data_rate'],
                $_POST['ram_voltage'],
                $_POST['ram_price'],
                $_POST['ram_date'],
                $_POST['ram_brand'],
                $newImageName,
                $editId
            );
            break;
        case 'GPU':
            $stmt = $conn->prepare("UPDATE GPU SET gpu_name = ?, gpu_chipset = ?, gpu_memory_size = ?, gpu_base_clock = ?, gpu_price = ?, gpu_date = ?, gpu_brand = ?, gpu_image = IFNULL(?, gpu_image) WHERE gpu_id = ?");
            $stmt->bind_param(
                "ssssssssi",
                $_POST['gpu_name'],
                $_POST['gpu_chipset'],
                $_POST['gpu_memory_size'],
                $_POST['gpu_base_clock'],
                $_POST['gpu_price'],
                $_POST['gpu_date'],
                $_POST['gpu_brand'],
                $newImageName,
                $editId
            );
            break;
    }

    if ($stmt->execute()) {
        echo "<script>alert('Successfully updated'); document.location.href = 'admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Database error: " . $stmt->error . "');</script>";
    }
}

// Delete item functionality
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    $deleteQuery = "DELETE FROM $table WHERE {$table}_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully'); window.location.href='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
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
    <link rel="icon" href="assets/metamatch.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="logo">
            <img src="assets/metamatch.png" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="admin-dashboard.php?category=cpu" class="<?= $category == 'cpu' ? 'active' : '' ?>">CPU</a>
            <a href="admin-dashboard.php?category=gpu" class="<?= $category == 'gpu' ? 'active' : '' ?>">GPU</a>
            <a href="admin-dashboard.php?category=ram" class="<?= $category == 'ram' ? 'active' : '' ?>">RAM</a>
        </nav>
        <a href="logout.php"><button class="button">Logout</button></a>
    </aside>

    <main class="main-content">
        <div class="crud-header">
            <h1 class="fw-bold text-neutral fs-sub-normal"><?= strtoupper($category) ?> Dashboard</h1>
            <div class="crud-buttons">
                <button id="addBtn" onclick="showAddModal()"><?= $addButtonLabel ?></button>
            </div>
        </div>

        <div class="sort-all">
            <label for="sort" class="sort-label fw-regular">Sort By:</label>
            <select id="sort" class="custom-select" onchange="applySort()">
                <option value="price_low_to_high" <?= $sortOption == 'price_low_to_high' ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="price_high_to_low" <?= $sortOption == 'price_high_to_low' ? 'selected' : '' ?>>Price: High to Low</option>
                <option value="newest_first" <?= $sortOption == 'newest_first' ? 'selected' : '' ?>>Newest First</option>
            </select>
        </div>

        <div class="crud-table">
            <table id="gpuTable">
                <thead>
                    <tr>
                        <?php foreach ($displayColumns as $header): ?>
                            <th><?= $header ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                  <tbody id="gpuTableBody">
                      <?php while ($row = mysqli_fetch_assoc($result)): ?>
                          <tr>
                              <?php foreach ($row as $key => $value): ?>
                                  <?php if (strpos($key, 'image') !== false): ?>
                                      <td>
                                          <img src="img/<?= htmlspecialchars($value); ?>" alt="<?= $category ?> Image" style="width: 50px; height: auto;">
                                      </td>
                                  <?php else: ?>
                                      <td><?= htmlspecialchars($value) ?></td>
                                  <?php endif; ?>
                              <?php endforeach; ?>
                              <td>                              
                              <a href="javascript:void(0);" onclick="showEditModal({
                                      id: '<?= $row[$category . "_id"] ?>',
                                      name: '<?= htmlspecialchars($row[$category . "_name"]) ?>',
                                      brand: '<?= htmlspecialchars($row[$category . "_brand"]) ?>',
                                      <?php if ($category === 'cpu'): ?>
                                          cores: '<?= htmlspecialchars($row["cpu_cores"]) ?>',
                                          clockSpeed: '<?= htmlspecialchars($row["cpu_clock_speed"]) ?>',
                                          socket: '<?= htmlspecialchars($row["cpu_socket"]) ?>',
                                      <?php elseif ($category === 'ram'): ?>
                                          brand: '<?= htmlspecialchars($row["ram_brand"]) ?>',
                                          capacity: '<?= htmlspecialchars($row["ram_capacity"]) ?>',
                                          speed: '<?= htmlspecialchars($row["ram_data_rate"]) ?>',
                                          voltage: '<?= htmlspecialchars($row["ram_voltage"]) ?>',
                                      <?php elseif ($category === 'gpu'): ?>
                                          brand: '<?= htmlspecialchars($row["gpu_brand"]) ?>',
                                          chipset: '<?= htmlspecialchars($row["gpu_chipset"]) ?>',
                                          memorySize: '<?= htmlspecialchars($row["gpu_memory_size"]) ?>',
                                          baseClock: '<?= htmlspecialchars($row["gpu_base_clock"]) ?>',
                                          
                                      <?php endif; ?>
                                      price: '<?= htmlspecialchars($row[$category . "_price"]) ?>',
                                      date: '<?= htmlspecialchars($row[$category . "_date"]) ?>'
                                  })" class="edit-link">
                                      <i class="edit-button fas fa-edit"></i>
                                  </a>

                                  <a href="admin-dashboard.php?delete_id=<?= $row[$category . '_id'] ?>&category=<?= $category ?>" onclick="return confirm('Are you sure?')">
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

  <!-- Modal -->
  <div id="addModal" class="modal">
      <div class="modal-content">
          <span class="close" onclick="closeAddModal()">&times;</span>
          <h2 class="fw-bold fs-normal">Add <?= strtoupper($category) ?></h2>
          <form action="admin-dashboard.php" id="addForm" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="component_category"  value="<?= strtoupper($category) ?>">
              <div id="productFields"></div>
              <button type="submit">Add</button>
          </form>
      </div>
  </div>


  <!-- Edit Modal -->
  <div id="editModal" class="modal">
      <div class="modal-content">
          <span class="close" onclick="closeEditModal()">&times;</span>
          <h2 class="fw-bold fs-normal">Edit <?= strtoupper($category) ?></h2>
          <form action="admin-dashboard.php" id="editForm" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="edit_id" id="edit_id">
              <input type="hidden" name="component_category" value="<?= strtoupper($category) ?>">
              <div id="editFields"></div>
              <button type="submit">Save Changes</button>
          </form>
      </div>
  </div>

  <script>
      const category = "<?= $category ?>";

      // Dynamically adjust the form based on category
      function showAddModal() {
        
          const productFields = document.getElementById("productFields");
          productFields.innerHTML = "";  // Reset fields

          // Shared fields
          productFields.innerHTML += `
              <label for="productName">Name:</label>
              <input type="text" id="${category}_name" name="${category}_name" required>
              
              <label for="productImage">Image:</label>
              <input type="file" id="${category}_image" name="${category}_image" required>
              
              <label for="productPrice">Price:</label>
              <input type="number" id="${category}_price" name="${category}_price" required>
              
              <label for="releaseDate">Release Date:</label>
              <input type="date" id="${category}_date" name="${category}_date" required>
          `;

          // Category-specific fields
          if (category === "cpu") {
              productFields.innerHTML += `
                  <label for="cpuBrand">Brand:</label>
                  <input type="text" id="cpuBrand" name="cpu_brand" required>

                  <label for="cpuCores">Cores:</label>
                  <input type="text" id="cpuCores" name="cpu_cores" required>

                  <label for="cpuTechnology">Technology:</label>
                  <input type="text" id="cpuTechnology" name="cpu_technology" required>

                  <label for="cpuClockSpeed">Clock Speed:</label>
                  <input type="text" id="cpuClockSpeed" name="cpu_clock_speed" required>
                  
                  <label for="cpuSocket">Socket:</label>
                  <input type="text" id="cpuSocket" name="cpu_socket" required>
              `;
          } else if (category === "ram") {
              productFields.innerHTML += `
                  <label for="ramBrand">Brand:</label>
                  <input type="text" id="ramBrand" name="ram_brand" required>

                  <label for="ramCapacity">Capacity:</label>
                  <input type="text" id="ramCapacity" name="ram_capacity" required>
                  
                  <label for="ramSpeed">Speed:</label>
                  <input type="text" id="ramSpeed" name="ram_data_rate" required>
                  
                  <label for="ramVoltage">Voltage:</label>
                  <input type="text" id="ramVoltage" name="ram_voltage" required>
              `;
          } else if (category === "gpu") {
              productFields.innerHTML += `
                <label for="gpuChipset">Chipset:</label>
                <input type="text" id="gpuChipset" name="gpu_chipset" required>
                
                <label for="gpuMemorySize">Memory Size:</label>
                <input type="text" id="gpuMemorySize" name="gpu_memory_size" required>
                
                <label for="gpuBaseClock">Base Clock:</label>
                <input type="text" id="gpuBaseClock" name="gpu_base_clock" required>
            `;
        }

        document.getElementById("addModal").style.display = "block";
    }

   // Show Edit Modal with pre-filled data
function showEditModal(rowData) {
    const editFields = document.getElementById("editFields");
    editFields.innerHTML = ""; // Reset fields

    // Shared fields
    editFields.innerHTML += `
        <label for="productName">Name:</label>
        <input type="text" id="edit_${category}_name" name="${category}_name" value="${rowData.name}" required>
        
        <label for="productImage">Image:</label>
        <input type="file" id="edit_${category}_image" name="${category}_image" accept="image/*">
        
        <label for="productPrice">Price:</label>
        <input type="number" id="edit_${category}_price" name="${category}_price" value="${rowData.price}" required>
        
        <label for="releaseDate">Release Date:</label>
        <input type="date" id="edit_${category}_date" name="${category}_date" value="${rowData.date}" required>
    `;

    // Category-specific fields
    if (category === "cpu") {
        editFields.innerHTML += `
            <label for="cpuBrand">Brand:</label>
            <input type="text" id="edit_cpuBrand" name="cpu_brand" value="${rowData.brand}" required>

            <label for="cpuCores">Cores:</label>
            <input type="text" id="edit_cpuCores" name="cpu_cores" value="${rowData.cores}" required>

            <label for="cpuTechnology">Technology:</label>
            <input type="text" id="edit_cpuTechnology" name="cpu_technology" value="${rowData.technology}" required>

            <label for="cpuClockSpeed">Clock Speed:</label>
            <input type="text" id="edit_cpuClockSpeed" name="cpu_clock_speed" value="${rowData.clockSpeed}" required>
            
            <label for="cpuSocket">Socket:</label>
            <input type="text" id="edit_cpuSocket" name="cpu_socket" value="${rowData.socket}" required>
        `;
    } else if (category === "ram") {
        editFields.innerHTML += `
            <label for="ramBrand">Brand:</label>
            <input type="text" id="edit_ramBrand" name="ram_brand" value="${rowData.brand}" required>

            <label for="ramCapacity">Capacity:</label>
            <input type="text" id="edit_ramCapacity" name="ram_capacity" value="${rowData.capacity}" required>
            
            <label for="ramSpeed">Speed:</label>
            <input type="text" id="edit_ramSpeed" name="ram_data_rate" value="${rowData.speed}" required>
            
            <label for="ramVoltage">Voltage:</label>
            <input type="text" id="edit_ramVoltage" name="ram_voltage" value="${rowData.voltage}" required>
        `;
    } else if (category === "gpu") {
        editFields.innerHTML += `
            <label for="gpuBrand">Brand:</label>
            <input type="text" id="edit_gpuBrand" name="gpu_brand" value="${rowData.brand}" required>

            <label for="gpuChipset">Chipset:</label>
            <input type="text" id="edit_gpuChipset" name="gpu_chipset" value="${rowData.chipset}" required>
            
            <label for="gpuMemorySize">Memory Size:</label>
            <input type="text" id="edit_gpuMemorySize" name="gpu_memory_size" value="${rowData.memorySize}" required>
            
            <label for="gpuBaseClock">Base Clock:</label>
            <input type="text" id="edit_gpuBaseClock" name="gpu_base_clock" value="${rowData.baseClock}" required>
        `;
    }

    document.getElementById("edit_id").value = rowData.id; // Set the row ID
    document.getElementById("editModal").style.display = "block";
}

function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}
// Close the Edit Modal
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

</script>


<script src="js/adminpage.js"></script>
</body>
</html>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

if (isset($_POST["submit"])) {
    $gpu_name = $_POST['gpu_name'];
    $gpu_chipset = $_POST['gpu_chipset'];
    $gpu_memory_size = $_POST['gpu_memory_size']; // for memory size (e.g., 24 GB)
    $gpu_base_clock = $_POST['gpu_base_clock'];  // for base clock speed (e.g., 1929 MHz)
    $gpu_boost_clock = $_POST['gpu_boost_clock']; // for boost clock speed (e.g., 2500 MHz)
    $gpu_shaders = $_POST['gpu_shaders'];        // number of shaders
    $gpu_date = $_POST['gpu_date'];
    $gpu_price = $_POST['gpu_price'];
    $gpu_brand = $_POST['gpu_brand'];

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
                $stmt = $conn->prepare("INSERT INTO GPU (gpu_name, gpu_chipset, gpu_memory_size, gpu_base_clock, gpu_boost_clock, gpu_shaders, gpu_date, gpu_image, gpu_price, gpu_brand) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "ssssssssss",
                    $gpu_name,
                    $gpu_chipset,
                    $gpu_memory_size,
                    $gpu_base_clock,
                    $gpu_boost_clock,
                    $gpu_shaders,
                    $gpu_date,
                    $newImageName,
                    $gpu_price,
                    $gpu_brand
                );

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Successfully added');
                            document.location.href = 'gpu-categories.php';
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload GPU Details</title>
</head>
<body>
    <h2>Upload GPU Details</h2>
    <form action="gpu-upload.php" method="POST" enctype="multipart/form-data">
        <label for="gpu_name">GPU Name:</label>
        <input type="text" name="gpu_name" id="gpu_name" required><br><br>

        <label for="gpu_chipset">GPU Chipset:</label>
        <input type="text" name="gpu_chipset" id="gpu_chipset" required><br><br>

        <label for="gpu_memory_size">Memory Size (e.g., 24 GB):</label>
        <input type="text" name="gpu_memory_size" id="gpu_memory_size" required><br><br>

        <label for="gpu_base_clock">Base Clock Speed (MHz):</label>
        <input type="number" name="gpu_base_clock" id="gpu_base_clock" required><br><br>

        <label for="gpu_boost_clock">Boost Clock Speed (MHz):</label>
        <input type="number" name="gpu_boost_clock" id="gpu_boost_clock" required><br><br>

        <label for="gpu_shaders">Number of Shaders:</label>
        <input type="number" name="gpu_shaders" id="gpu_shaders" required><br><br>

        <label for="gpu_date">Release Date:</label>
        <input type="date" name="gpu_date" id="gpu_date" required><br><br>

        <label for="gpu_image">GPU Image:</label>
        <input type="file" name="gpu_image" id="gpu_image" accept=".jpg, .jpeg, .png" required><br><br>

        <label for="gpu_price">GPU Price (in PHP):</label>
        <input type="number" step="0.01" name="gpu_price" id="gpu_price" required><br><br>

        <label for="gpu_brand">GPU Brand:</label>
        <input type="text" name="gpu_brand" id="gpu_brand" required><br><br>

        <button type="submit" name="submit">Upload GPU</button>
    </form>
    <br>

    <a href="gpu-categories.php">Categories</a>
</body>
</html>

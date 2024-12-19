<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

if (isset($_POST["submit"])) {
    $ram_name = $_POST['ram_name'];
    $ram_capacity = $_POST['ram_capacity'];
    $ram_data_rate = $_POST['ram_data_rate'];
    $ram_voltage = $_POST['ram_voltage'];
    $ram_timing = $_POST['ram_timing'];
    $ram_brand = $_POST['ram_brand'];
    $ram_date = $_POST['ram_date'];
    $ram_price = $_POST['ram_price'];

    if ($_FILES["ram_image"]["error"] === 4) {
        echo "<script>alert('Image does not exist');</script>";
    } else {
        $fileName = $_FILES["ram_image"]["name"];
        $fileSize = $_FILES["ram_image"]["size"];
        $tmpName = $_FILES["ram_image"]["tmp_name"];

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
                $stmt = $conn->prepare("INSERT INTO RAM (ram_name, ram_capacity, ram_data_rate, ram_voltage, ram_timing, ram_brand, ram_date, ram_image, ram_price) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param(
                    "sssssssss",
                    $ram_name,
                    $ram_capacity,
                    $ram_data_rate,
                    $ram_voltage,
                    $ram_timing,
                    $ram_brand,
                    $ram_date,
                    $newImageName,
                    $ram_price
                );

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Successfully added');
                            document.location.href = 'ram-categories.php';
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
    <title>Upload RAM Details</title>
</head>
<body>
    <h2>Upload RAM Details</h2>
    <form action="ram-upload.php" method="POST" enctype="multipart/form-data">
        <label for="ram_name">RAM Name:</label>
        <input type="text" name="ram_name" id="ram_name" required><br><br>

        <label for="ram_capacity">RAM Capacity (GB):</label>
        <input type="number" name="ram_capacity" id="ram_capacity" required><br><br>

        <label for="ram_data_rate">RAM Data Rate (MHz):</label>
        <input type="text" name="ram_data_rate" id="ram_data_rate" required><br><br>

        <label for="ram_voltage">RAM Voltage (V):</label>
        <input type="number" step="0.01" name="ram_voltage" id="ram_voltage" required><br><br>

        <label for="ram_timing">RAM Timing:</label>
        <input type="text" name="ram_timing" id="ram_timing" required><br><br>

        <label for="ram_brand">RAM Brand:</label>
        <input type="text" name="ram_brand" id="ram_brand" required><br><br>

        <label for="ram_date">RAM Release Date:</label>
        <input type="date" name="ram_date" id="ram_date" required><br><br>

        <label for="ram_image">RAM Image:</label>
        <input type="file" name="ram_image" id="ram_image" accept=".jpg, .jpeg, .png" required><br><br>

        <label for="ram_price">RAM Price (in PHP):</label>
        <input type="number" step="0.01" name="ram_price" id="ram_price" required><br><br>

        <button type="submit" name="submit">Upload RAM</button>
    </form>
    <br>

    <a href="ram-categories.php">Categories</a>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

if (isset($_POST["submit"])) {
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
                            document.location.href = 'cpu-categories.php';
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
    <title>Upload CPU Details</title>
</head>
<body>
    <h2>Upload CPU Details</h2>
    <form action="cpu-upload.php" method="POST" enctype="multipart/form-data">
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
    <br>

    <a href="cpu-categories.php">Categories</a>
</body>
</html>
